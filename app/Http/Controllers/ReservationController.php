<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Http\Requests\UpdateShopRequest;

class ReservationController extends Controller
{
public function update(Request $request, $id)
{
    $reservation = Reservation::find($id);

    if (!$reservation) {
      return redirect()->back()->with('error', '予約が見つかりませんでした');
    }

    $request_data = $request->all();

    if (isset($request_data['datepicker']) && isset($request_data['time'])) {
      $datetime_str = $request_data['datepicker'].' '.$request_data['time'].':00';
      $request_data['start_at'] = $datetime_str;
    }
    Log::info('Backend date value: ' . $request_data['datepicker']);
    Log::info('Backend time value: ' . $request_data['time']);
    Log::info('Backend datetime value: ' . $datetime_str);

    $reservation->update($request_data);

    return redirect()->route('mypage');
  }

  public function delete(Request $request)
  {
    $reservation = Reservation::findOrFail($request->reservation_id);
    $reservation->delete();

    return redirect()->route('mypage');
  }
}