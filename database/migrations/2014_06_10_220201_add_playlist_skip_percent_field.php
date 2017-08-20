<?php

use Illuminate\Database\Migrations\Migration;

class AddPlaylistSkipPercentField extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlists', function ($table) {
            $table->decimal('user_skip_threshold', 2, 2)// decimal percentage
            ->default(0.5)// 50% of active sessions
            ->after('max_duplicates');
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
            $table->dropColumn('user_skip_threshold');
        });
    }

}
