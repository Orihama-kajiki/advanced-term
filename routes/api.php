<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\StripeWebhookController;

Route::middleware(['auth:sanctum', 'verified', 'checkRole:利用者'])->group(function () {
  Route::post('/reservations', [ReservationController::class, 'store'])
    ->middleware([\App\Http\Middleware\ConvertEmptyStringsToNull::class])
    ->name('reservations.store');
  Route::post('/create-checkout-session', [ReservationController::class, 'createCheckoutSession']);
});

Route::get('/reservations/{id}', [ReservationController::class, 'show']);
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
