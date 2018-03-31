<?php

use Illuminate\Database\Migrations\Migration;

class AddRoleUserTimestamps extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_user', function ($table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_user', function ($table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }

}
