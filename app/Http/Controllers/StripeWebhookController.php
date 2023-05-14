<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
  public function handleWebhook(Request $request)
  {
    $payload = json_decode($request->getContent(), true);

    switch ($payload['type']) {
      case 'checkout.session.completed':
        $session = $payload['data']['object'];
        Reservation::createReservation(
          $session['metadata']['shop_id'],
          $session['metadata']['user_id'],
          $session['metadata']['num_of_users'],
          $session['metadata']['start_at'],
          $session['metadata']['course_menu_id']
        );
      break;
    }

    return response()->json(['status' => 'success']);
  }
}
