<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function webhook(Request $request, string $currency)
    {
        $validCurrencies = ['btc', 'lightning', 'usdc', 'xmr'];
        if (!in_array($currency, $validCurrencies)) {
            return response()->json(['error' => 'Invalid currency'], 400);
        }

        $payload = $request->all();

        if (empty($payload)) {
            return response()->json(['error' => 'Empty payload'], 400);
        }

        app(PaymentManager::class)->handleWebhook($currency, $payload);

        return response()->json(['status' => 'ok']);
    }
}