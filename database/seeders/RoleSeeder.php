<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Zeropingheroes\Lanager\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // don't seed if table is not empty
        if (Role::count()) {
            return;
        }

        $roles = [
            [
                'name' => 'super-admin',
                'display_name' => __('seeder.super-admin'),
            ],
            [
                'name' => 'admin',
                'display_name' => __('seeder.admin'),
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
