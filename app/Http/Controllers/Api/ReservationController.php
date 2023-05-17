<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use App\Models\CourseMenu;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Log; 
use Stripe\Stripe;
use Stripe\Checkout\Session;

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

  public function store(ReservationRequest $request)
  {
    Log::info('storeMethod was called.');
    Log::info('Request data:', $request->all());
    $start_at = $request->start_at . ' ' . $request->time;

    $reservationId = Reservation::createReservation(
      $request->shop_id,
      $request->user_id,
      $request->num_of_users,
      $start_at,
      $request->course_menu_id
    );
    Log::info('Reservation ID: ' . $reservationId);
    if ($reservationId) {
      return response()->json(['reservation_id' => $reservationId], 200);
    } else {
      return response()->json(['message' => 'Failed to create reservation'], 500);
    }
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

  public function createCheckoutSession(Request $request)
  {
    $course_menu_id = $request->input('course_menu_id');
    $course_menu = CourseMenu::find($course_menu_id); 

    if ($course_menu_id !== null && $course_menu) {
      try {
        Stripe::setApiKey(env('STRIPE_SECRET'));

      $checkout_session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
          'price_data' => [
            'currency' => 'jpy',
            'product_data' => [
              'name' => 'Reservation',
            ],
            'unit_amount' => $course_menu->price, 
          ],
          'quantity' => $request->num_of_users, 
        ]],
        'mode' => 'payment',
        'success_url' => route('done'),
        'cancel_url' => route('shops.index'),
        'metadata' => [
          'shop_id' => $request->shop_id,
          'user_id' => $request->user_id,
          'num_of_users' => $request->num_of_users,
          'start_at' => $request->start_at,
          'course_menu_id' => $request->course_menu_id
        ]
      ]);

      return response()->json(['url' => $checkout_session->url]);
      } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
      }
    } else if ($course_menu_id === null) {
      return response()->json(['message' => 'No course menu selected']);
    } else {
      return response()->json(['error' => 'Invalid course menu id']);
    }
  }
}