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
        $merchantWallet = config('crypto.evm.merchant_wallet');

        if (!$merchantWallet) {
            $merchantWallet = '0x' . bin2hex(random_bytes(20));
        }

        Payment::create([
            'order_id' => $order->id,
            'payment_address' => $merchantWallet,
            'amount_expected' => $order->crypto_amount,
            'status' => 'pending',
        ]);

        return [
            'address' => $merchantWallet,
            'amount' => $order->crypto_amount,
            'currency' => 'USDC',
            'chain' => $this->chainName(),
        ];
    }

    public function verifyPayment(string $orderId): string
    {
        $order = Order::where('order_id', $orderId)->firstOrFail();
        $payment = $order->payment;

        $rpcUrl = config('crypto.evm.rpc_url');
        $contractAddress = config('crypto.evm.contract_address');

        if (!$rpcUrl || !$contractAddress) {
            return 'pending';
        }

        $transferTopic = '0xddf252ad1be2c89b69c2b068fc378daa952ba7f163c4a11628f55a4df523b3ef';
        $toTopic = '0x' . str_pad(ltrim(str_replace('0x', '', $payment->payment_address), '0'), 64, '0', STR_PAD_LEFT);

        $response = Http::timeout(10)->post($rpcUrl, [
            'jsonrpc' => '2.0',
            'method' => 'eth_getLogs',
            'params' => [[
                'fromBlock' => '0x0',
                'toBlock' => 'latest',
                'address' => $contractAddress,
                'topics' => [$transferTopic, null, $toTopic],
            ]],
            'id' => 1,
        ]);

        if (!$response->successful()) return 'pending';

        $logs = $response->json()['result'] ?? [];

        if (count($logs) > 0) {
            $latestLog = $logs[count($logs) - 1];
            $amountHex = $latestLog['data'] ?? '0x0';
            $amount = hexdec($amountHex) / 1e6;

            if ($amount >= (float) $payment->amount_expected) {
                $payment->update([
                    'amount_received' => $amount,
                    'tx_hash' => $latestLog['transactionHash'] ?? '',
                    'status' => 'paid',
                    'verified_at' => now(),
                ]);
                $order->update(['status' => 'paid', 'paid_at' => now()]);
                return 'paid';
            }
        }

        return 'pending';
    }

    public function handleWebhook(array $payload): void
    {
        $txHash = $payload['transactionHash'] ?? $payload['tx_hash'] ?? null;
        $status = $payload['status'] ?? '';

        if (!$txHash) return;

        $payment = Payment::where('tx_hash', $txHash)->first();
        if (!$payment) return;

        $payment->update([
            'webhook_payload' => $payload,
        ]);

        if ($status === 'confirmed' || $status === 'settled') {
            $payment->update([
                'amount_received' => $payload['amount'] ?? $payment->amount_expected,
                'status' => 'paid',
                'verified_at' => now(),
            ]);
            $payment->order->update(['status' => 'paid', 'paid_at' => now()]);
        }
    }

    private function chainName(): string
    {
        $chainId = config('crypto.evm.chain_id', 1);

        return match ($chainId) {
            1 => 'Ethereum',
            137 => 'Polygon',
            42161 => 'Arbitrum',
            10 => 'Optimism',
            8453 => 'Base',
            11155111 => 'Sepolia (testnet)',
            default => "Chain {$chainId}",
        };
    }
}