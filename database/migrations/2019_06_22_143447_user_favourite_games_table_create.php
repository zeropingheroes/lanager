<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFavouriteGamesTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_favourite_games', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->integer('lan_id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
            $table->integer('favouriteable_id')
                ->unsigned();
            $table->string('favouriteable_type');
            $table->timestamps();

            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('user_favourite_games');
    }
}
