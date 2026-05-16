<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'currency', 'price', 'price_usd',
        'file_path', 'thumbnail', 'active',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'price_usd' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}