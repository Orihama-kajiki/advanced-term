<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Http\Requests\ReservationRequest;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $start_at = $request->start_at . ' ' . $request->time;

        Reservation::createReservation(
            $request->shop_id,
            $request->user_id,
            $request->num_of_users,
            $start_at,
            $request->course_menu_id
        );

        return redirect()->route('done');
    }

    public function delete(Request $request)
    {
        $reservation = Reservation::findOrFail($request->reservation_id);
        $reservation->delete();

        return redirect()->route('mypage');
    }

}