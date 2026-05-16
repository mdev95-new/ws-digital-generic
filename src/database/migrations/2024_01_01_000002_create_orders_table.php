<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 20)->unique();
            $table->foreignId('product_id')->constrained();
            $table->string('currency', 10);
            $table->decimal('crypto_amount', 20, 8);
            $table->decimal('usd_amount', 10, 2);
            $table->string('status', 20)->default('pending');
            $table->string('delivery_method', 20);
            $table->string('delivery_contact');
            $table->string('pin_hash')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};