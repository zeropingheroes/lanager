<?php

use Illuminate\Database\Migrations\Migration;

class ChangePlaylistMaxDuplicates extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlists', function ($table) {
            $table->dropColumn('max_duplicates');
            $table->tinyInteger('max_item_duplicates')
                ->unsigned()
                ->default(0)
                ->after('max_item_duration');

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

            $table->dropColumn('max_item_duplicates');
            $table->tinyInteger('max_duplicates')
                ->unsigned()
                ->default(0)
                ->after('playback_state');
        });
    }

}
