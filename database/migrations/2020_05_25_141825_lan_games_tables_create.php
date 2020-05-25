<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LanGamesTablesCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lan_games', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->integer('lan_id')
                ->unsigned();
            $table->string('game_name');
            $table->integer('created_by')
                ->unsigned();
            $table->timestamps();

            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('lan_game_votes', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->integer('lan_game_id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
            $table->timestamps();

            $table->foreign('lan_game_id')
                ->references('id')
                ->on('lan_games')
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
        Schema::dropIfExists('lan_game_votes');
        Schema::dropIfExists('lan_games');
    }
}
