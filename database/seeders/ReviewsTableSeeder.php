<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review; 
use App\Models\User; 
use App\Models\Shop; 
use Faker\Factory as Faker;

class ReviewsTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = Faker::create('ja_JP');
    $users = User::where('email', '!=', 'admin@example.com')
                    ->where('email', '!=', 'owner@example.com')
                    ->where('email', '!=', 'user@example.com')
                    ->get();
    $shops = Shop::all();

    foreach ($users as $user) {
      foreach ($shops as $shop) {
        if (rand(0, 1)) {
          Review::create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'rating' => rand(1, 5),
            'comment' => $faker->realText(50), 
          ]);
        }
      }
    }
  }
}
