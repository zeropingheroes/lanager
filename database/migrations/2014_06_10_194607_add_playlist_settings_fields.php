<?php

use Illuminate\Database\Migrations\Migration;

class AddPlaylistSettingsFields extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlists', function ($table) {
            $table->tinyInteger('max_duplicates')
                ->unsigned()
                ->default(0)
                ->after('playback_state');
            $table->integer('max_item_duration')
                ->unsigned()
                ->default(600)// 10 minutes
                ->after('playback_state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlists', function ($table) {
            $table->dropColumn('max_duplicates');
            $table->dropColumn('max_item_duration');
        });
    }

}
