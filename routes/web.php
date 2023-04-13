<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/', [ShopController::class, 'index']);
Route::get('/detail/{shop_id}', [ShopController::class, 'detail'])->name('detail');
Route::middleware('guest')->group(function () {
  Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
  Route::post('register', [RegisteredUserController::class, 'store']);
  Route::get('register/thanks', function () {
  return view('thanks');})->name('register.thanks');
  Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
  Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
  Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
  Route::get('/mypage/favorites', [UserController::class, 'getFavoriteShops'])->name('mypage.favorites');
  Route::post('/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
  Route::post('/reserve', [ReservationController::class, 'store'])->name('reserve.store');
  Route::get('/done', function () {
      return view('done');
  })->name('done');
  Route::post('/reserve/delete', [ReservationController::class, 'delete'])->name('reserve.delete');
  Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
