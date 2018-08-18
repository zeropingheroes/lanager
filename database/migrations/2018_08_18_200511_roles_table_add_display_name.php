<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Zeropingheroes\Lanager\Role;

class RolesTableAddDisplayName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->text('display_name')
                ->after('name');
        });

        // If there are existing roles in the database, generate the name from display name
        if (Role::count()) {
            $roles = Role::all();
            foreach($roles as $role) {
                $role->display_name = $role->name;
                $role->name = kebab_case($role->name);
                $role->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });

        // If there are existing roles in the database, revert to the old name
        if (Role::count()) {
            $roles = Role::all();
            foreach($roles as $role) {
                $role->name = str_replace('-', ' ', title_case($role->name));
                $role->save();
            }
        }
    }
}
