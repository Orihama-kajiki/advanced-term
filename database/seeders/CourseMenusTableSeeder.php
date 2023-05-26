<?php

namespace Database\Seeders;

use App\Models\CourseMenu;
use Illuminate\Database\Seeder;


class CourseMenusTableSeeder extends Seeder
{
  public function run()
  {
    $course_menus = [];

  for ($shop_id = 1; $shop_id <= 20; $shop_id++) {
    for ($i = 1; $i <= 3; $i++) {
      $course_menus[] = [
        'shop_id' => $shop_id,
        'name' => "コース料理{$i}",
        'price' => 5000 * $i,
        'description' => "美味しいコース料理{$i}の説明文",
      ];
    }
  }

    foreach ($course_menus as $course_menu) {
      CourseMenu::create($course_menu);
    }
  }
}
