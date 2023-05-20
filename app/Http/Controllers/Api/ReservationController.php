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

    $start_at = $request->start_at; 

    $reservation = Reservation::create([
      'shop_id' => $request->shop_id,
      'user_id' => $request->user_id,
      'num_of_users' => $request->num_of_users,
      'start_at' => $start_at,
      'course_menu_id' => $request->course_menu_id
    ]);

    if ($reservation->id) {
      return redirect()->route('done'); 
    } else {
      return response()->json(['message' => 'Failed to create reservation'], 500);
    }
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
        'cancel_url' => route('payment.cancel'),
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