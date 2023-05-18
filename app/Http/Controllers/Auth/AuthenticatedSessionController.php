<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{

  public function create()
  {
    return view('auth.login');
  }

public function store(LoginRequest $request)
{
  $request->authenticate();

  $request->session()->regenerate();

  $user = Auth::user();
  $token = $user->createToken('token-name')->plainTextToken;

  $redirectUrl = '/';
  if ($user->hasRole('管理者')) {
    $redirectUrl = route('admin.index');
  } else if ($user->hasRole('店舗責任者')) {
    $redirectUrl = route('owner.index');
  } else {
    $redirectUrl = route('shops.index');
  }

  return response()->json([
    'token' => $token,
    'redirectUrl' => $redirectUrl
  ]);
}

  public function destroy(Request $request)
  {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
  }

}
