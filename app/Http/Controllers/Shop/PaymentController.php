<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Initialize the Paystack transaction and return a redirect link.
     */
    public function initialize(Request $request)
    {
        // Paystack expects amount in cents (e.g. R150.00 = 15000 cents)
        $amountInCents = $request->amount * 100;

        $response = Http::withToken(config('services.paystack.secret'))
            ->post(config('services.paystack.url').'/transaction/initialize', [
                'email' => auth()->user()->email,
                'amount' => $amountInCents,
                'callback_url' => route('shop.payment.callback'),
            ]);

        if ($response->successful()) {
            return redirect($response->json()['data']['authorization_url']);
        }

        return back()->with('error', 'Paystack payment initialization failed.');
    }

    /**
     * Handle the return callback from Paystack to verify payment status.
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        $response = Http::withToken(config('services.paystack.secret'))
            ->get(config('services.paystack.url')."/transaction/verify/{$reference}");

        if ($response->successful() && $response->json()['data']['status'] === 'success') {
            // Payment successful! Your teammate can handle order status logic here
            return redirect()->route('shop.index')->with('success', 'Payment successful!');
        }

        return redirect()->route('shop.index')->with('error', 'Payment verification failed.');
    }
}
