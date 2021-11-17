<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    const PAYMENT_ENDPOINT_BASE = 'https://eu-test.oppwa.com/';

    public function index($id = null)
    {
        return view('pay', [
            'checkoutId' => $id,
            'isPayment' => $id !== null
        ]);
    }

    public function pay(Request $request)
    {
        $validatedDetails = $request->validate([
            'amount' => ['required'],
            'reference' => ['required']
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
                return redirect("/pay/{$checkoutId}");
            }
        }

        return 'oops!'; // TODO: Proper error handling and display 
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

            $res = var_export($response, true);

            return "<pre>$res</pre>"; // view('result', []);
        }

        return 'oops!'; // TODO: Proper error handling and display 
    }
}
