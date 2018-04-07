<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserOAuthAccountsTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

        Schema::table('users', function ($table) {
            $table->dropColumn('provider');
            $table->dropColumn('provider_id');
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

        Schema::table('users', function (Blueprint $table) {
            $table->string('provider')
                  ->after('username');
            $table->string('provider_id')
                ->after('provider');
        });
    }
}
