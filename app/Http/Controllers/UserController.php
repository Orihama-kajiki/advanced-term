<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;

class UserController extends Controller
{
    public function mypage()
    {
        $user = Auth::user();
        $reservations = $user->reservations()->with('shop')->get();
        $favoriteShops = $user->favorites;
        return view('mypage', compact('reservations', 'favoriteShops'));
    }

}
