<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Reservation extends Model
{
  use HasFactory;

  protected $fillable = [
    'shop_id',
    'user_id',
    'num_of_users',
    'start_at',
    'course_menu_id',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

  public function course_menu()
  {
    return $this->belongsTo(CourseMenu::class);
  }

  public static function validate($data)
  {
    $rules = [
      'num_of_users' => 'required|integer',
      'start_at' => 'required|date',
      'course_menu_id' => 'nullable|exists:course_menus,id',
    ];

    return Validator::make($data, $rules);
  }

  public static function createReservation($shop_id, $user_id, $num_of_users, $start_at, $course_menu_id)
  {
    $reservation = new self();
    $reservation->shop_id = $shop_id;
    $reservation->user_id = $user_id;
    $reservation->num_of_users = $num_of_users;
    $reservation->start_at = $start_at;
    $reservation->course_menu_id = $course_menu_id;

    if ($reservation->save()) {
      return $reservation->id;
    } else {
      return false;
    }
  }
}
