<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = 'shops'; 
    protected $fillable = ['name', 'area_id','genre_id','description','image_url']; 

    public function users()
    {
        return $this->belongsToMany(User::class, 'reservations', 'user_id', 'shop_id')->withPivot('num_of_users', 'start_at')->withTimestamps();
    }    

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

        public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'shop_id', 'user_id')->withTimestamps();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }


}


