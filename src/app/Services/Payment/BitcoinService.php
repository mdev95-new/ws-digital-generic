<?php

namespace App\Services\Payment;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;

class BitcoinService implements PaymentInterface
{
    public function currency(): string
    {
        return 'btc';
    }

    public function createInvoice(Order $order): array
    {
        $xpub = config('crypto.btc.xpub');
        $network = config('crypto.btc.network');

        $address = $this->deriveAddress($xpub, $order->id, $network);

        Payment::create([
            'order_id' => $order->id,
            'payment_address' => $address,
            'amount_expected' => $order->crypto_amount,
            'status' => 'pending',
        ]);

        return [
            'address' => $address,
            'amount' => $order->crypto_amount,
            'currency' => 'BTC',
            'network' => $network,
        ];
    }

    public function verifyPayment(string $orderId): string
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $payment = $order->payment;

        $url = $payment->network === 'testnet'
            ? 'https://blockstream.info/testnet/api'
            : 'https://blockstream.info/api';

        $response = Http::get("{$url}/address/{$payment->payment_address}/txs");

        if (!$response->successful()) {
            return 'pending';
        }

        $txs = $response->json();
        $received = 0;

        foreach ($txs as $tx) {
            foreach ($tx['vout'] as $vout) {
                if (in_array($payment->payment_address, $vout['scriptpubkey_address'] ?? [])) {
                    $received += $vout['value'];
                }
            }
        }

        $receivedBtc = $received / 100000000;

        if ($receivedBtc >= (float) $payment->amount_expected) {
            $payment->update([
                'amount_received' => $receivedBtc,
                'tx_hash' => $txs[0]['txid'] ?? null,
                'status' => 'paid',
                'verified_at' => now(),
            ]);
            $order->update(['status' => 'paid', 'paid_at' => now()]);
            return 'paid';
        }

        $payment->update(['amount_received' => $receivedBtc]);
        return 'pending';
    }

    public function handleWebhook(array $payload): void
    {
        $txHash = $payload['txid'] ?? null;
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

    private function deriveAddress(string $xpub, int $index, string $network): string
    {
        // Uses bitcoinjs-lin via Node.js microservice, or PHP library
        // For now returns placeholder — will implement with furstic/php-bitcoin-utils
        return hash('sha256', $xpub . $index);
    }
}