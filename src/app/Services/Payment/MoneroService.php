<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class MoneroService implements PaymentInterface
{
    public function currency(): string
    {
        return 'xmr';
    }

    public function createInvoice(Order $order): array
    {
        $rpcUrl = config('crypto.monero.rpc_url');

        $response = Http::withBasicAuth('user', 'pass')
            ->post("{$rpcUrl}/json_rpc", [
                'jsonrpc' => '2.0',
                'method' => 'create_address',
                'params' => [
                    'label' => "Order {$order->order_id}",
                ],
            ]);

        $address = $response->json()['result']['address'] ?? 'xmr_' . bin2hex(random_bytes(16));

        Payment::create([
            'order_id' => $order->id,
            'payment_address' => $address,
            'amount_expected' => $order->crypto_amount,
            'status' => 'pending',
        ]);

        return [
            'address' => $address,
            'amount' => $order->crypto_amount,
            'currency' => 'XMR',
        ];
    }

    public function verifyPayment(string $orderId): string
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $payment = $order->payment;
        $rpcUrl = config('crypto.monero.rpc_url');

        $response = Http::withBasicAuth('user', 'pass')
            ->post("{$rpcUrl}/json_rpc", [
                'jsonrpc' => '2.0',
                'method' => 'get_transfers',
                'params' => [
                    'in' => true,
                    'account_index' => 0,
                ],
            ]);

        if (!$response->successful()) return 'pending';

        $transfers = $response->json()['result']['in'] ?? [];

        foreach ($transfers as $tx) {
            if ($tx['address'] === $payment->payment_address) {
                $amount = $tx['amount'] / 1e12;
                if ($amount >= (float) $payment->amount_expected) {
                    $payment->update([
                        'amount_received' => $amount,
                        'tx_hash' => $tx['txid'],
                        'status' => 'paid',
                        'verified_at' => now(),
                    ]);
                    $order->update(['status' => 'paid', 'paid_at' => now()]);
                    return 'paid';
                }
            }
        }

        return 'pending';
    }

    public function handleWebhook(array $payload): void
    {
        $txHash = $payload['tx_id'] ?? null;
        $address = $payload['address'] ?? null;

        if (!$txHash || !$address) return;

        $payment = Payment::where('payment_address', $address)->first();
        if (!$payment) return;

        $payment->update([
            'tx_hash' => $txHash,
            'webhook_payload' => $payload,
        ]);

        $this->verifyPayment($payment->order->order_id);
    }
}