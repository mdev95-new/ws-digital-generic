<?php

return [
    'btc' => [
        'xpub' => env('CRYPTO_BTC_XPUB'),
        'network' => env('CRYPTO_BTC_NETWORK', 'testnet'),
        'derivation_path' => "0/0",
    ],
    'lightning' => [
        'endpoint' => env('CRYPTO_LN_ENDPOINT', 'http://btcpay:49392'),
        'api_key' => env('CRYPTO_LN_KEY'),
    ],
    'evm' => [
        'rpc_url' => env('CRYPTO_EVM_RPC'),
        'chain_id' => (int) env('CRYPTO_EVM_CHAIN', 11155111),
        'contract_address' => env('CRYPTO_EVM_CONTRACT'),
    ],
    'monero' => [
        'rpc_url' => env('CRYPTO_XMR_RPC', 'http://monero:18089'),
    ],
];