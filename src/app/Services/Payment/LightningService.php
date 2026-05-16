<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class LightningService implements PaymentInterface
{
    public function currency(): string
    {
        return 'lightning';
    }

    public function createInvoice(Order $order): array
    {
        $endpoint = config('crypto.lightning.endpoint');
        $apiKey = config('crypto.lightning.api_key');

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
        ])->post("{$endpoint}/invoices", [
            'amount' => $order->crypto_amount,
            'description' => "Order {$order->order_id}",
        ]);

        $invoice = $response->json();

        Payment::create([
            'order_id' => $order->id,
            'payment_address' => $invoice['payment_hash'] ?? '',
            'amount_expected' => $order->crypto_amount,
            'status' => 'pending',
        ]);

        return [
            'invoice' => $invoice['payment_request'] ?? '',
            'payment_hash' => $invoice['payment_hash'] ?? '',
            'amount' => $order->crypto_amount,
            'currency' => 'sats',
            'expires_at' => $invoice['expires_at'] ?? null,
        ];
    }

    public function verifyPayment(string $orderId): string
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $payment = $order->payment;
        $endpoint = config('crypto.lightning.endpoint');

        $response = Http::withHeaders([
            'Authorization' => "Bearer " . config('crypto.lightning.api_key'),
        ])->get("{$endpoint}/invoices/{$payment->payment_address}");

        if (!$response->successful()) return 'pending';

        $data = $response->json();

        if (($data['settled'] ?? false) || ($data['status'] ?? '') === 'settled') {
            $payment->update([
                'amount_received' => $data['amount_received'] ?? $payment->amount_expected,
                'status' => 'paid',
                'verified_at' => now(),
            ]);
            $order->update(['status' => 'paid', 'paid_at' => now()]);
            return 'paid';
        }

        return 'pending';
    }

    public function handleWebhook(array $payload): void
    {
        $paymentHash = $payload['payment_hash'] ?? null;
        if (!$paymentHash) return;

        $payment = Payment::where('payment_address', $paymentHash)->first();
        if (!$payment) return;

        $payment->update([
            'webhook_payload' => $payload,
        ]);

        $this->verifyPayment($payment->order->order_id);
    }
}