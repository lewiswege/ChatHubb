<?php

use App\Http\Controllers\Webhooks\SimulatorWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Webhook routes (public, no auth, verified by signature)
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/simulator', [SimulatorWebhookController::class, 'handle'])->name('simulator');
    // Add more channels as we build them:
    // Route::post('/telegram', [TelegramWebhookController::class, 'handle'])->name('telegram');
    // Route::post('/whatsapp', [WhatsAppWebhookController::class, 'handle'])->name('whatsapp');
});
