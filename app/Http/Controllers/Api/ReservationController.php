<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; 

class ReservationController extends Controller
{
    public function show($id)
    {
        $reservation = Reservation::find($id);

        if ($reservation === null) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        $reservation->time = date('H:i', strtotime($reservation->start_at));
        $reservation->date = date('Y-m-d', strtotime($reservation->start_at));

        return response()->json($reservation);
    }

    public function store(Request $request)
    {
        $validator = Reservation::validate($request->all());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $start_at = $request->start_at . ' ' . $request->time;

        $reservation = Reservation::createReservation(
            $request->shop_id,
            $request->user_id,
            $request->num_of_users,
            $start_at
        );

        return response()->json(['reservation' => $reservation], 201);
    }

    public function update(Request $request, $id)
    {
        $request_data = $request->all();
        $request_data['start_at'] = $request_data['datetime'];
        unset($request_data['datetime']); 

        $validator = Reservation::validate($request_data);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => '予約が見つかりませんでした'], 404);
        }

        $utcDateTime = Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $request_data['start_at'], 'UTC');
        $tokyoDateTime = $utcDateTime->setTimezone('Asia/Tokyo');
        $start_at = $tokyoDateTime->format('Y-m-d H:i:s');

        $updateData = array_merge($request_data, ['start_at' => $start_at]);
        $reservation->update($updateData);

        return response()->json(['message' => '予約が更新されました', 'reservation' => $reservation], 200);
    }

    public function delete(Request $request, Reservation $reservation)
    {
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully'], 200);
    }

}

