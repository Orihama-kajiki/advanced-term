<?php

namespace Database\Seeders;

use App\Models\Shop;
use Illuminate\Database\Seeder;

class AssignAdminToShopsSeeder extends Seeder
{
  public function run()
  {
    $adminUserId = 1;

    Shop::all()->each(function ($shop) use ($adminUserId) {
      $shop->user_id = $adminUserId;
      $shop->save();
    });
  }
}