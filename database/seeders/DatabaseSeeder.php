<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  public function run()
  {
    $this->call([
      RolesAndPermissionsSeeder::class,
      AreasTableSeeder::class,
      GenresTableSeeder::class,
      UsersTableSeeder::class,
      ShopsTableSeeder::class,
      CourseMenusTableSeeder::class,
      ReviewsTableSeeder::class,
    ]);
  }
}
