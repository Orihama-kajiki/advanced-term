<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationReminder extends Mailable
{
  use Queueable, SerializesModels;

  public $reservation;

  public function __construct(Reservation $reservation)
  {
    $this->reservation = $reservation;
  }

  public function build()
  {
    return $this->subject('予約リマインダー')
      ->view('vendor.notifications.reservation-reminder') 
      ->with([
        'reservation' => $this->reservation
      ]);
  }
}
