<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Middleware\VerifyWebhook;

Route::post('/webhook/{currency}', [PaymentController::class, 'webhook'])
    ->middleware(VerifyWebhook::class . ':{currency}')
    ->name('webhook.payment');