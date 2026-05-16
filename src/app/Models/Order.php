<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Order extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'currency', 'crypto_amount', 'usd_amount',
        'status', 'delivery_method', 'delivery_contact', 'pin_hash',
        'paid_at', 'delivered_at', 'expires_at',
    ];

    protected $casts = [
        'crypto_amount' => 'decimal:8',
        'usd_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function verifyPin(string $pin): bool
    {
        if (!$this->pin_hash) {
            return true;
        }
        return Hash::check($pin, $this->pin_hash);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}