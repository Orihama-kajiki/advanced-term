<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validator = Reservation::validate($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $start_at = $request->start_at . ' ' . $request->time;

        Reservation::createReservation(
            $request->shop_id,
            $request->user_id,
            $request->num_of_users,
            $start_at
        );

        return redirect()->route('done');
    }

    public function delete(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'reservation_id' => 'required|exists:reservations,id',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->route('mypage')->withErrors($validator);
        // }

        $reservation = Reservation::findOrFail($request->reservation_id);
        $reservation->delete();

        return redirect()->route('mypage');
    }

}