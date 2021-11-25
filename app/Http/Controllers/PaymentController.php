<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class PaymentController extends Controller
{
    const REGEX_PREPARE_SUCCESS = '/^(000\.200)/';

    public function index($id = null)
    {
        return view('pay', [
            'checkoutId' => $id,
            'isPayment' => $id !== null
        ]);
    }

    public function history() 
    {
        // In a production app I'd be paginating this properly, 
        // but we're only going to have a few records here... 
        $payments = Payment::where('user_id', Auth::id())->get();

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
            'paymentType' => $payment->type,
            'paymentBrand' => $payment->brand
        ];
    }

    protected static function validatePrepareResponse($response): bool 
    {
        $code = $response['result']['code'] ?? null;
        return $code !== null && preg_match(self::REGEX_PREPARE_SUCCESS, $code) === 1;
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

        $prepareUrl = Config::get('copyandpay.base_url') . 'v1/checkouts';
        $entityId = Config::get('copyandpay.entity_id');
        $accessToken = Config::get('copyandpay.access_token');

        $response = Http::asForm()
            ->withToken($accessToken)
            ->post($prepareUrl, self::makePrepareBody($payment, $entityId))
            ->throw()
            ->json();

        if (!self::validatePrepareResponse($response)) {
            return abort(500, 'Could not prepare checkout');
        }
        
        // Save the pending payment
        $payment->save();

        return redirect("/pay/{$response['id']}");
    }

    protected static function validateResultResponse($response): bool 
    {
        $code = $response['result']['code'] ?? null;
        return $code !== null && preg_match(self::REGEX_PREPARE_SUCCESS, $code) === 1;
    }

    public function result(Request $request)
    {
        $resourcePath = $request->query('resourcePath');

        if ($resourcePath === null) {
            return abort(500, 'Could not get checkout result');
        }

        $resultUrl = Config::get('copyandpay.base_url') . $resourcePath;
        $entityId = Config::get('copyandpay.entity_id');
        $accessToken = Config::get('copyandpay.access_token');

        $response = Http::withToken($accessToken)
            ->get($resultUrl, ['entityId' => $entityId])
            ->throw()
            ->json();

        if (!self::validateResultResponse($response)) {
            return abort(500, 'Could not process payment');
        }

        $resultCode = $response['result']['code'];
        $resultDesc = $response['result']['description'];
        $merchantTxId = $response['merchantTransactionId'];

        // Update payment record with result
        $payment = Payment::where('merchant_tx_id', $merchantTxId)->update([
            'result_code' => $resultCode,
            'result_desc' => $resultDesc
        ]);

        $resultMsg = $resultCode === self::SUCCESS_RESULT_CODE
            ? 'Successful'
            : 'Failed';
        
        return view('result', [
            'resultMsg' => $resultMsg,
            'resultCode' => $resultCode,
            'resultDesc' => $resultDesc
        ]);
    }
}
