<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMenu extends Model
{
  use HasFactory;

  protected $fillable = [
    'shop_id',
    'name',
    'price',
    'description',
  ];

  public function shop()
  {
    return $this->belongsTo(Shop::class);
  }

    public function reservations()
  {
      return $this->hasMany(Reservation::class);
  }
}