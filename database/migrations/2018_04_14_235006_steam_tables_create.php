<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SteamTablesCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_apps', function ($table) {
            $table->integer('id')
                ->unsigned()
                ->unique();

            $table->string('name');
            $table->primary('id');
        });

        Schema::create('steam_app_servers', function ($table) {

            $table->increments('id');

            $table->integer('steam_app_id')
                ->unsigned();

            $table->string('address');    // ipv4, hostname, or ipv6

            $table->integer('port')
                ->nullable();

            $table->timestamps();

            // Relationships
            $table->foreign('steam_app_id')
                ->references('id')
                ->on('steam_apps')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('steam_user_status_codes', function ($table) {
            $table->smallInteger('id')
                ->unsigned()
                ->unique();

            $table->string('name');
            $table->primary('id');
        });

        Schema::create('steam_user_states', function ($table) {

            $table->increments('id');

            $table->integer('user_id')
                ->unsigned();

            $table->integer('steam_app_id')
                ->unsigned()
                ->nullable(); // nullable to prevent circular dependencies

            $table->integer('steam_app_server_id')
                ->unsigned()
                ->nullable();  // nullable to prevent circular dependencies

            $table->smallInteger('steam_user_status_code_id')
                ->unsigned();

            $table->timestamps();

            // Relationships
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('steam_app_id')
                ->references('id')
                ->on('steam_apps')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('steam_app_server_id')
                ->references('id')
                ->on('steam_app_servers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('steam_user_status_code_id')
                ->references('id')
                ->on('steam_user_status_codes')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('steam_user_apps', function ($table) {

            $table->increments('id');

            $table->integer('user_id')
                ->unsigned();

            $table->integer('steam_app_id')
                ->unsigned()
                ->nullable(); // nullable to prevent circular dependencies

            $table->integer('playtime_two_weeks')
                ->unsigned()
                ->default(0);

            $table->integer('playtime_forever')
                ->unsigned()
                ->default(0);

            $table->timestamps();

            // Relationships
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('steam_app_id')
                ->references('id')
                ->on('steam_apps')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('steam_user_metadata', function ($table) {

            $table->increments('id');

            $table->integer('user_id')
                ->unsigned();

            $table->boolean('profile_visible')
                ->nullable();

            $table->boolean('apps_visible')
                ->nullable();

            $table->timestamp('profile_updated_at')
                ->nullable();

            $table->timestamp('apps_updated_at')
                ->nullable();

            // Relationships
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('steam_user_metadata');
        Schema::drop('steam_user_apps');
        Schema::drop('steam_user_states');
        Schema::drop('steam_user_status_codes');
        Schema::drop('steam_app_servers');
        Schema::drop('steam_apps');
    }
}
