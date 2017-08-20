<?php

use Illuminate\Database\Migrations\Migration;

class MovePlaylistDescription extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE playlists MODIFY COLUMN description VARCHAR(255) AFTER name');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE playlists MODIFY COLUMN description VARCHAR(255) AFTER updated_at');
    }

}
