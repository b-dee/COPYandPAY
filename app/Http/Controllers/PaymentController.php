<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    const PAYMENT_ENDPOINT_BASE = 'https://eu-test.oppwa.com/';
    const SUCCESS_RESULT_CODE = '000.100.110'; // Integrator Test Mode success

    public function index($id = null)
    {
        return view('pay', [
            'checkoutId' => $id,
            'isPayment' => $id !== null
        ]);
    }

    public function history() {
        // In a production app I'd be paginating this properly, 
        // but we're only going to have a few records here... 
        $payments = Payment::where('user_id', Auth::id())->get();

        return view('history', [
            'payments' => $payments
        ]);
    }

    public function pay(Request $request)
    {
        // Just some simple validation, not too realistic
        $validatedDetails = $request->validate([
            'amount' => ['required', 'min:0.01', 'max:999999', 'numeric'],
            'reference' => ['required', 'min:8', 'max:50']
        ]);

        $url = self::PAYMENT_ENDPOINT_BASE . 'v1/checkouts';
        $entityId = env('COPY_AND_PAY_ENTITY_ID');
        $accessToken = env('COPY_AND_PAY_ACCESS_TOKEN');
        $merchantTxId = $validatedDetails['reference'];
        $amount = strval($validatedDetails['amount']);

        // Hardcode these for now
        // TODO: Allow merchant to set these
        $currency = 'GBP';
        $paymentType = 'DB';

        $data = "entityId={$entityId}" .
            "&merchantTransactionId={$merchantTxId}" .
            "&amount={$amount}" .
            "&currency={$currency}" .
            "&paymentType={$paymentType}";
        
        
        $response = Http::withToken($accessToken)
            ->withBody($data, 'application/x-www-form-urlencoded')
            ->post($url);

        if ($response->ok()) {
            $res = $response->json();
            if (is_array($res) && isset($res['id'])) {
                $checkoutId = $res['id'];

                // Store merchant tx reference for use later
                $payment = new Payment();
                $payment->user_id = Auth::id();
                $payment->merchant_tx_id = $merchantTxId;
                $payment->amount = $amount;
                $payment->currency = $currency;
                $payment->save();

                return redirect("/pay/{$checkoutId}");
            }
        }

        // TODO: Proper error handling and display in a real app
        // I won't implement here since this is a demo app
        return 'oops!';
    }

    public function result(Request $request)
    {
        $resourcePath = $request->query('resourcePath');

        if ($resourcePath !== null) {
            $url = self::PAYMENT_ENDPOINT_BASE . $resourcePath;
            $entityId = env('COPY_AND_PAY_ENTITY_ID');
            $accessToken = env('COPY_AND_PAY_ACCESS_TOKEN');

            $response = Http::withToken($accessToken)->get($url, [
                'entityId' => $entityId
            ]);

            if ($response->ok()) {
                $res = $response->json();
                if (is_array($res)) {
                    $resultCode = $res['result']['code'];
                    $resultDesc = $res['result']['description'];
                    $merchantTxId = $res['merchantTransactionId'];

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

        }

        // TODO: Proper error handling and display in a real app
        // I won't implement here since this is a demo app
        return 'oops!';
    }
}
