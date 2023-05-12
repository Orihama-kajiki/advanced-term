<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShopOwnerController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{shop_id}', [ShopController::class, 'detail'])->name('detail');
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
  $user = User::find($id);
  if (!$user || !hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
      abort(403);
  }
  $user->markEmailAsVerified();
  return redirect()->route('finish');
})->name('verification.verify')->middleware(['signed', 'throttle:6,1']);
Route::get('/finish', [UserController::class, 'finish'])->name('finish');

Route::get('shop-owner/reservation-detail/{id}/qr-code', [ShopOwnerController::class, 'generateQrCode'])->name('owner.reservation-detail.qr-code');

Route::middleware('guest')->group(function () {
  Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
  Route::post('register', [RegisteredUserController::class, 'store']);
  Route::get('register/thanks', function () {
  return view('thanks');})->name('register.thanks');
  Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
  Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth'])->group(function () {
  Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'verified', 'checkRole:利用者'])->group(function () {
  Route::get('/mypage', [UserController::class, 'mypage'])->name('mypage');
  Route::post('/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
  Route::get('reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
  Route::post('reviews/store', [ReviewController::class, 'store'])->name('reviews.store');
  Route::post('/reserve', [ReservationController::class, 'store'])->name('reserve.store');
  Route::get('/done', function () {
      return view('done');})->name('done');
  Route::post('/reserve/delete', [ReservationController::class, 'delete'])->name('reserve.delete');
});

Route::middleware(['auth', 'checkRole:管理者'])->group(function () {
  Route::get('admin/top', [AdminController::class, 'index'])->name('admin.index');
  Route::get('/admin/create-account', [AdminController::class, 'create'])->name('admin.create-account');
  Route::post('/admin/store-account', [AdminController::class, 'store'])->name('admin.store-account');
  Route::get('/admin/create-email', [AdminController::class, 'createEmail'])->name('admin.create-email');
  Route::post('/admin/send-email', [AdminController::class, 'sendEmail'])->name('admin.send-email');
});

Route::middleware(['auth', 'checkRole:店舗責任者'])->group(function () {
  Route::get('shop-owner/top', [ShopOwnerController::class, 'index'])->name('owner.index');
  Route::get('shop-owner/reservation-list', [ShopOwnerController::class, 'reservationList'])->name('owner.reservation-list');
  Route::get('shop-owner/reservation-detail/{id}', [ShopOwnerController::class, 'reservationDetail'])->name('owner.reservation-detail');
  Route::put('shop-owner/reservation-update/{id}', [ShopOwnerController::class, 'updateReservation'])->name('owner.reservation-update');
  Route::get('shop-owner/create', [ShopOwnerController::class, 'create'])->name('owner.create-shop');
  Route::post('shop-owner/store', [ShopOwnerController::class, 'store'])->name('owner.store-shop');
  Route::get('shop-owner/{id}/edit', [ShopOwnerController::class, 'edit'])->name('owner.edit-shop');
  Route::put('shop-owner/{id}/update', [ShopOwnerController::class, 'update'])->name('owner.update-shop');
  Route::delete('shop-owner/{shop}/delete', [ShopOwnerController::class, 'destroy'])->name('owner.delete-shop');
});