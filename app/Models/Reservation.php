<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Reservation extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public static function validate($data)
    {
        $rules = [
            'shop_id' => 'required|integer',
            'user_id' => 'required|integer',
            'num_of_users' => 'required|integer',
            'start_at' => 'required|date',
        ];

        return Validator::make($data, $rules);
    }

    public static function createReservation($shop_id, $user_id, $num_of_users, $start_at)
    {
        $reservation = new self();
        $reservation->shop_id = $shop_id;
        $reservation->user_id = $user_id;
        $reservation->num_of_users = $num_of_users;
        $reservation->start_at = $start_at;
        $reservation->save();
    }
}
