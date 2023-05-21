<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'User Test',
            'email' => 'user@test.com',
            'password' => Hash::make('password'), // あるいはbcrypt('password')
            // その他必要なフィールド...
        ]);

        // 同様に他のテストプレイ用アカウントを作成...
    }
}
