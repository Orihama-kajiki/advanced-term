<?php

namespace App\Console;

use App\Models\Reservation;
use App\Notifications\ReservationReminder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  protected function schedule(Schedule $schedule)
  {
    $schedule->call(function () {
      $reservations = Reservation::whereDate('start_at', now()->startOfDay())->get();

      foreach ($reservations as $reservation) {
        Mail::to($reservation->user->email)->send(new ReservationReminder($reservation));
      }
    })->everyMinute();
  }

  protected function commands()
  {
    $this->load(__DIR__.'/Commands');

    require base_path('routes/console.php');
  }
}
