<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Http\Requests\ReservationRequest;

class ReservationController extends Controller
{

    public function delete(Request $request)
    {
        $reservation = Reservation::findOrFail($request->reservation_id);
        $reservation->delete();

        return redirect()->route('mypage');
    }

}