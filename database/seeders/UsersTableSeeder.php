<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; 
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::where('name', '管理者')->first();
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin')
        ]);
        $admin->assignRole($adminRole);

        $ownerRole = Role::where('name', '店舗責任者')->first();
        $owner = User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('owner')
        ]);
        $owner->assignRole($ownerRole);

        $userRole = Role::where('name', '利用者')->first();
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('user')
        ]);
        $user->assignRole($userRole);

        User::factory(100)->create()->each(function ($user) use ($userRole, $ownerRole) {
            if ($user->id % 2 == 0) {
                $user->assignRole($userRole);
            } else {
                $user->assignRole($ownerRole);
            }
        });
    }
}
