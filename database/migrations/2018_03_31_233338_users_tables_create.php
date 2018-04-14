<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersTablesCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_oauth_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                ->unsigned();
            $table->string('username')
                ->nullable();
            $table->string('provider');
            $table->string('provider_id');
            $table->timestamps();
            $table->string('avatar')
                ->nullable();
            $table->string('access_token')
                ->nullable();
            $table->timestamp('token_expiry')
                ->nullable();
            $table->string('refresh_token')
                ->nullable();


            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_oauth_accounts');
        Schema::dropIfExists('users');
    }
}
