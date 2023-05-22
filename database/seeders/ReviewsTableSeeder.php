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
        
        $users = User::where('id', '>=', 31)->where('id', '<=', 130)->get();
        $shops = Shop::where('id', '>=', 1)->where('id', '<=', 20)->get();

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