<?php

use Illuminate\Database\Migrations\Migration;

class ChangeUserSkipThresholdPt2 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlists', function ($table) {
            $table->tinyInteger('user_skip_threshold')
                ->unsigned()
                ->default(50)
                ->after('max_item_duplicates');
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
