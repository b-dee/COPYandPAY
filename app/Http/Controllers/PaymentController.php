<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{
    const ENDPOINT_PREPARE = 'v1/checkouts';

    const REGEX_CHECKOUT_ID = '/[a-zA-Z0-9.\-]{32,48}/';
    const REGEX_PREPARE_SUCCESS = '/^(000\.200)/';
    const REGEX_TRANSACTION_SUCCESS = '/^(000\.000\.|000\.100\.1|000\.[36])/';

    const CACHE_PREFIX_PAYMENT_HISTORY = 'payment_history_';

    // Deliberately set to low number so you don't need to wait to see changes
    const CACHE_TTL_PAYMENT_HISTORY = 15; // Seconds

    // Deliberately set to low number so you don't need lots of payments to see pagination
    const PER_PAGE_PAYMENT_HISTORY = 2;

    public function __construct()
    {
        $this->entityId = Config::get('copyandpay.entity_id');
        $this->accessToken = Config::get('copyandpay.access_token');
        $this->paymentBaseUrl = Config::get('copyandpay.base_url');
        $this->prepareCheckoutUrl = $this->paymentBaseUrl . self::ENDPOINT_PREPARE;
    }

    public function index(string $id = null)
    {
        return view('pay', [
            'checkoutId' => $id,
            'isPayment' => $id !== null
        ]);
    }

    protected static function cacheKey(array $params, string $prefix = ''): string
    {
        $str = '';
        foreach ($params as $param) {
            $str .= strval($param) . '_';
        }
        return $prefix . md5($str);
    }

    public function history(Request $request) 
    {
        $userId = Auth::id();
        $page = $request->query('page', 1);

        $key = self::cacheKey([ $userId, $page ], self::CACHE_PREFIX_PAYMENT_HISTORY);

        // Cache result of history query
        $payments = Cache::remember($key, self::CACHE_TTL_PAYMENT_HISTORY, function () use ($userId) {
            return Payment::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->simplePaginate(self::PER_PAGE_PAYMENT_HISTORY);
        });

        return view('history', [
            'payments' => $payments
        ]);
    }

    protected static function makePrepareBody(Payment $payment, string $entityId): array 
    {
        return [
            'entityId' => $entityId,
            'merchantTransactionId' => $payment->merchant_tx_id,
            'amount' => $payment->amount,
            'currency' => $payment->currency,
            'paymentType' => $payment->type
        ];
    }

    protected static function isPrepareSuccessful(array $response): bool 
    {
        $id = $response['id'] ?? null;
        $code = $response['result']['code'] ?? null;
        
        return  $id !== null 
            &&  $code !== null 
            &&  preg_match(self::REGEX_PREPARE_SUCCESS, $code) === 1;
    }

    public function pay(Request $request)
    {
        $valid = $request->validate([
            'amount' => [
                'required', 
                'numeric', 
                'min:0.01', 
                'max:9999999999.99'
            ],
            'reference' => [
                'required', 
                'string', 
                'min:8', 
                'max:255', 
                'unique:App\Models\Payment,merchant_tx_id'
            ]
        ]);

        $payment = new Payment();
        $payment->user_id = Auth::id();
        $payment->merchant_tx_id = $valid['reference'];
        $payment->amount = $valid['amount'];
        $payment->currency = 'GBP';
        $payment->type = 'DB';
        $payment->brand = 'VISA';

        $body = self::makePrepareBody($payment, $this->entityId);

        $response = Http::asForm()
            ->withToken($this->accessToken)
            ->post($this->prepareCheckoutUrl, $body)
            ->throw()
            ->json();

        if (!self::isPrepareSuccessful($response)) {
            return abort(500, 'Could not prepare checkout');
        }
        
        $checkoutId = $response['id'];
        $payment->checkout_id = $checkoutId;
        $payment->save(); // Save the pending payment

        return redirect("/pay/{$checkoutId}");
    }

    protected static function isPaymentSuccessful(array $response): bool 
    {
        $code = $response['result']['code'] ?? null;
        return $code !== null && preg_match(self::REGEX_TRANSACTION_SUCCESS, $code) === 1;
    }

    protected static function validateStatusResponse(array $response, Payment $payment): bool
    {
        return  $response['merchantTransactionId'] === $payment->merchant_tx_id
            &&  $response['amount'] === strval($payment->amount)
            &&  $response['currency'] === $payment->currency
            &&  $response['paymentType'] === $payment->type
            &&  $response['paymentBrand'] === $payment->brand;
    }

    public function result(Request $request)
    {
        $valid = $request->validate([
            'id' => [ 'required', 'string', 'max:48' ],
            'resourcePath' => [ 'required', 'string' ]
        ]);

        // Do we have record of this payment?
        $payment = Payment::firstWhere([
            'user_id' => Auth::id(),
            'checkout_id' => $valid['id']
        ]);

        if ($payment === null) {
            return abort(400, 'Payment not recognised');
        }

        $resultUrl = $this->paymentBaseUrl . $valid['resourcePath'];

        $response = Http::withToken($this->accessToken)
            ->get($resultUrl, ['entityId' => $this->entityId])
            ->throw()
            ->json();

        // Does the status response match the stored payment?
        if (!self::validateStatusResponse($response, $payment)) {
            return abort(500, 'Could not get checkout result');
        }

        $success = self::isPaymentSuccessful($response);
        $payment->result_code = $response['result']['code'] ?? null;
        $payment->result_desc = $response['result']['description'] ?? null;

        // Update payment record with id and result
        $payment->payment_id = $response['id'];
        $payment->save();

        return view('result', [
            'success' => $success,
            'resultCode' => $payment->result_code ?? 'None received',
            'resultDesc' => $payment->result_desc ?? 'None received',
            'resultMsg' => $success ? 'Successful' : 'Failed'
        ]);
    }
}
