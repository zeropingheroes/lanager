<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function ($table) {
            // Fields
            $table->increments('id');

            $table->string('username', 32);

            $table->string('steam_id_64', 17)
                ->unique();

            $table->string('ip', 15);

            $table->string('avatar', 255);

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
        Schema::drop('users');
    }

}