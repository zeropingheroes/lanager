<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GamesTablesDrop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('epic_games');
        Schema::dropIfExists('origin_games');
        Schema::dropIfExists('blizzard_games');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('epic_games', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->text('name');
            $table->text('url')
                ->nullable();
            $table->timestamps();
        });
        Schema::create('origin_games', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->text('name');
            $table->text('url');
            $table->timestamps();
        });
        Schema::create('blizzard_games', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->text('name');
            $table->text('url')
                ->nullable()
                ->default(null);
            $table->timestamps();
        });
    }
}
