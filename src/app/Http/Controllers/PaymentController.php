<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentManager;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function webhook(Request $request, string $currency)
    {
        $payload = $request->all();

        app(PaymentManager::class)->handleWebhook($currency, $payload);

        return response()->json(['status' => 'ok']);
    }
}