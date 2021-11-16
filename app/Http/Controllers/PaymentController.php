<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
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

        $url = 'https://eu-test.oppwa.com/v1/checkouts';
        $entityId = env('COPY_AND_PAY_ENTITY_ID');
        $accessToken = env('COPY_AND_PAY_ACCESS_TOKEN');
        $merchantTxId = $validatedDetails['reference'];
        $amount = strval($validatedDetails['amount']);

        // Hardcode these for now
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

        return 'oops!';
    }

    public function result(Request $request)
    {
        return 'This is the results page...';
    }
}
