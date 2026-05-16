<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class EvmService implements PaymentInterface
{
    public function currency(): string
    {
        return 'usdc';
    }

    public function createInvoice(Order $order): array
    {
        $address = '0x' . bin2hex(random_bytes(20));

        Payment::create([
            'order_id' => $order->id,
            'payment_address' => $address,
            'amount_expected' => $order->crypto_amount,
            'status' => 'pending',
        ]);

        return [
            'address' => $address,
            'amount' => $order->crypto_amount,
            'currency' => 'USDC',
            'chain' => 'Ethereum',
        ];
    }

    public function verifyPayment(string $orderId): string
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $payment = $order->payment;

        $rpcUrl = config('crypto.evm.rpc_url');
        $chainId = config('crypto.evm.chain_id');

        $response = Http::post($rpcUrl, [
            'jsonrpc' => '2.0',
            'method' => 'eth_getLogs',
            'params' => [[
                'address' => config('crypto.evm.contract_address'),
                'topics' => [null, '0x' . str_pad(ltrim($payment->payment_address, '0x'), 64, '0', STR_PAD_LEFT)],
            ]],
            'id' => 1,
        ]);

        if (!$response->successful()) return 'pending';

        $logs = $response->json()['result'] ?? [];

        if (count($logs) > 0) {
            $payment->update([
                'amount_received' => $payment->amount_expected,
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
        $txHash = $payload['transactionHash'] ?? null;
        $to = $payload['to'] ?? null;

        if (!$txHash || !$to) return;

        $payment = Payment::where('payment_address', $to)->first();
        if (!$payment) return;

        $payment->update([
            'tx_hash' => $txHash,
            'webhook_payload' => $payload,
        ]);

        $this->verifyPayment($payment->order->order_id);
    }
}