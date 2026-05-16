<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->string('tx_hash', 128)->nullable()->index();
            $table->string('payment_address', 128);
            $table->decimal('amount_expected', 20, 8);
            $table->decimal('amount_received', 20, 8)->default(0);
            $table->integer('confirmations')->default(0);
            $table->string('status', 20)->default('pending');
            $table->json('webhook_payload')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};