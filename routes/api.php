<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\StripeWebhookController;


Route::middleware(['auth:sanctum', 'verified', 'checkRole:利用者'])->group(function () {
  Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
  Route::post('/create-checkout-session', [ReservationController::class, 'createCheckoutSession']);
  Route::get('/favorites', [FavoriteController::class, 'index']);
  Route::post('/favorites/{shopId}', [FavoriteController::class, 'store']);
});

Route::get('/reservations/{id}', [ReservationController::class, 'show']);
Route::put('/reservations/{id}', [ReservationController::class, 'update']);
Route::delete('/reservations/{id}', [ReservationController::class, 'delete'])->name('reservations.delete');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);