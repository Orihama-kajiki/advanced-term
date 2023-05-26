<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Genre;

class GenresTableSeeder extends Seeder
{
  public function run()
  {
    $genres = ['寿司', '焼肉', '居酒屋', 'イタリアン', 'ラーメン'];
    $timestamp = now();

    foreach ($genres as $genre) {
      DB::table('genres')->insert([
        'name' => $genre,
        'created_at' => $timestamp,
        'updated_at' => $timestamp,
      ]);
    }
  }
}
