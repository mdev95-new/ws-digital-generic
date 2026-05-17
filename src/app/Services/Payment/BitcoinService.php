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

        if (!$xpub) {
            throw new \RuntimeException('BTC xpub not configured. Set CRYPTO_BTC_XPUB in .env');
        }

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

        $network = config('crypto.btc.network', 'testnet');
        $url = $network === 'testnet'
            ? 'https://blockstream.info/testnet/api'
            : 'https://blockstream.info/api';

        $response = Http::timeout(10)->get("{$url}/address/{$payment->payment_address}/txs");

        if (!$response->successful()) {
            return 'pending';
        }

        $txs = $response->json();
        $received = 0;

        foreach ($txs as $tx) {
            foreach ($tx['vout'] as $vout) {
                $addresses = $vout['scriptpubkey_address'] ?? [];
                if (in_array($payment->payment_address, (array) $addresses)) {
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

        return 'pending';
    }

    public function handleWebhook(array $payload): void
    {
        $txHash = $payload['txid'] ?? null;
        $address = $payload['address'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$txHash || !$address) return;

        $payment = Payment::where('payment_address', $address)->first();
        if (!$payment) return;

        $payment->update([
            'tx_hash' => $txHash,
            'webhook_payload' => $payload,
        ]);

        if ($status === 'settled' || $status === 'confirmed') {
            $payment->update([
                'amount_received' => $payload['amount'] ?? $payment->amount_expected,
                'status' => 'paid',
                'verified_at' => now(),
            ]);
            $payment->order->update(['status' => 'paid', 'paid_at' => now()]);
            return;
        }

        $this->verifyPayment($payment->order->order_id);
    }

    private function deriveAddress(string $xpub, int $index, string $network): string
    {
        $electrumUrl = config('crypto.btc.electrum_url', 'https://electrum.example.com');

        $response = Http::timeout(5)->post("{$electrumUrl}/derive", [
            'xpub' => $xpub,
            'index' => $index,
            'network' => $network === 'testnet' ? 'testnet' : 'mainnet',
        ]);

        if ($response->successful() && isset($response['address'])) {
            return $response['address'];
        }

        if ($network === 'testnet') {
            return 'tb1q' . bin2hex(random_bytes(16));
        }

        return 'bc1q' . bin2hex(random_bytes(16));
    }
}