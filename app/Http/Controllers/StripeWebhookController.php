<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class StripeWebhookController extends Controller
{
  public function handleWebhook(Request $request)
  {
    try {
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
    } catch (\Exception $e) {
      \Log::error('Error handling Stripe webhook: '.$e->getMessage());
      return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
  }
}
