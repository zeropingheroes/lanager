<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LanAttendeeGamePicksTableDrop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('lan_attendee_game_picks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('lan_attendee_game_picks', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->integer('lan_id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
            $table->string('game_id_type');
            $table->integer('game_id')
                ->unsigned();
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
}