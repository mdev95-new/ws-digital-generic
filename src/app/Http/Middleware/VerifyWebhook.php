<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyWebhook
{
    public function handle(Request $request, Closure $next, string $currency)
    {
        if ($currency === 'btc' || $currency === 'lightning') {
            $secret = config("crypto.{$currency}.webhook_secret");
            $signature = $request->header('BTCPay-Sig');

            if ($secret && $signature) {
                $payload = $request->getContent();
                $expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);

                if (!hash_equals($expected, $signature)) {
                    abort(401, 'Invalid webhook signature.');
                }
            }
        }

        if ($currency === 'usdc') {
            $secret = config('crypto.evm.webhook_secret');
            $signature = $request->header('X-Webhook-Signature');

            if ($secret && $signature) {
                $payload = $request->getContent();
                $expected = hash_hmac('sha256', $payload, $secret);

                if (!hash_equals($expected, $signature)) {
                    abort(401, 'Invalid webhook signature.');
                }
            }
        }

        return $next($request);
    }
}