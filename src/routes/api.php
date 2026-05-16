<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/webhook/{currency}', [PaymentController::class, 'webhook'])->name('webhook.payment');