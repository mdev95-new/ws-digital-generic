<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'tx_hash', 'payment_address', 'amount_expected',
        'amount_received', 'confirmations', 'status', 'webhook_payload',
        'verified_at',
    ];

    protected $casts = [
        'amount_expected' => 'decimal:8',
        'amount_received' => 'decimal:8',
        'webhook_payload' => 'json',
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}