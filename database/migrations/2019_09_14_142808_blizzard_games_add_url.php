<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BlizzardGamesAddUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blizzard_games', function (Blueprint $table) {
            $table->text('url')
                ->after('name')
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blizzard_games', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }
}
