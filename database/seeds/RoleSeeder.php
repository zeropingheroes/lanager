<?php

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
        $roles = [
            ['name' => 'Super Admin'],
            ['name' => 'Admin'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
