<?php

use Illuminate\Database\Migrations\Migration;

class DropPlaylistTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('playlist_item_votes');
        Schema::drop('playlist_items');
        Schema::drop('playlists');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('playlists', function ($table) {
            // Fields
            $table->increments('id')
                ->unsigned();

            $table->string('name');

            $table->string('description')
                ->nullable();

            $table->integer('playback_state')
                ->unsigned()
                ->default(1);

            $table->integer('max_item_duration')
                ->unsigned()
                ->default(60);

            $table->integer('user_skip_threshold')
                ->unsigned()
                ->default(50);

            $table->timestamps();

        });

        Schema::create('playlist_items', function ($table) {
            // Fields
            $table->increments('id');

            $table->integer('playlist_id')
                ->unsigned();

            $table->integer('user_id')
                ->unsigned();

            $table->text('url');

            $table->string('title');

            $table->integer('playback_state')
                ->default(0)
                ->unsigned();

            $table->integer('duration')
                ->unsigned();

            $table->string('skip_reason')
                ->nullable();

            $table->timestamps();

            // Relationships
            $table->foreign('playlist_id')
                ->references('id')
                ->on('playlists')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('playlist_item_votes', function ($table) {
            // Fields
            $table->increments('id');

            $table->integer('playlist_item_id')
                ->unsigned();

            $table->integer('user_id')
                ->unsigned();

            $table->timestamps();

            // Relationships
            $table->foreign('playlist_item_id')
                ->references('id')
                ->on('playlist_items')
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
