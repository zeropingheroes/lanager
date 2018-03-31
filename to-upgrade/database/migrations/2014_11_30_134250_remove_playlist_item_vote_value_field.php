<?php

use Illuminate\Database\Migrations\Migration;

class RemovePlaylistItemVoteValueField extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('playlist_item_votes', function ($table) {
            $table->dropColumn('vote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('playlist_item_votes', function ($table) {
            $table->tinyInteger('vote');    // -1 for downvote, +1 for upvote
        });
    }

}
