<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function mypage()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $reservations = $user->reservations()->where('start_at', '>=', $now)->with('shop')->get();
        $pastReservations = $user->reservations()->where('start_at', '<', $now)->with('shop')->get();
        $favoriteShops = $user->favorites;
        $reviews = $user->reviews()->get();

        return view('mypage', compact('reservations', 'pastReservations', 'favoriteShops', 'reviews'));
    }

    public function finish()
    {
        return view('finish');
    }
}
