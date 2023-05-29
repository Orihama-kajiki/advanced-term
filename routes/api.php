<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\StripeWebhookController;

Route::middleware(['auth:sanctum', 'checkRole:利用者'])->group(function () {
  Route::get('/favorites', [FavoriteController::class, 'index']);
  Route::post('/favorites/{shopId}', [FavoriteController::class, 'store']);
  Route::post('/reservations', [ReservationController::class, 'store'])
    ->middleware([\App\Http\Middleware\ConvertEmptyStringsToNull::class])
    ->name('reservations.store');
});

Route::middleware(['auth:sanctum', 'verified', 'checkRole:利用者'])->group(function () {
  Route::post('/create-checkout-session', [ReservationController::class, 'createCheckoutSession']);
});

Route::get('/reservations/{id}', [ReservationController::class, 'show']);
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
