<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LanAttendeeGamePicksRenameGameProvider extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lan_attendee_game_picks', function (Blueprint $table) {
            $table->renameColumn('game_provider', 'game_id_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lan_attendee_game_picks', function (Blueprint $table) {
            $table->renameColumn('game_id_type', 'game_provider');
        });
    }
}
