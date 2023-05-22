<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'user_create',
            'user_edit',
            'user_delete',
            'shop_create',
            'shop_edit',
            'shop_delete',
            'reservation_create',
            'reservation_edit',
            'reservation_delete',
            'favorite-access',
            'review-create',
            'review-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $adminRole = Role::create(['name' => '管理者']);
        $adminRole->syncPermissions($permissions);

        $shopOwnerRole = Role::create(['name' => '店舗責任者']);
        $shopOwnerRole->syncPermissions([
            'shop_create',
            'shop_edit',
            'shop_delete',
            'reservation_create',
            'reservation_edit',
            'reservation_delete',
        ]);

        $userRole = Role::create(['name' => '利用者']);
        $userRole->syncPermissions([
            'reservation_create',
            'reservation_edit',
            'reservation_delete',
            'favorite-access',
            'review-create',
        ]);
    }
}
