<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReleaseV100 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('instance')
                ->index();
            $table->string('channel')
                ->index();
            $table->string('level')
                ->index();
            $table->string('level_name');
            $table->text('message');
            $table->longText('context');
            $table->integer('remote_addr')
                ->nullable()
                ->unsigned();
            $table->string('user_agent')
                ->nullable();
            $table->integer('created_by')
                ->nullable()
                ->index();
            $table->boolean('read')
                ->default(false);
            $table->timestamps();
        });
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')
                ->unique();
            $table->unsignedInteger('user_id')
                ->nullable();
            $table->string('ip_address', 45)
                ->nullable();
            $table->text('user_agent')
                ->nullable();
            $table->text('payload');
            $table->integer('last_activity');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('user_oauth_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                ->unsigned();
            $table->string('username')
                ->nullable();
            $table->string('provider');
            $table->string('provider_id');
            $table->timestamps();
            $table->string('avatar')
                ->nullable();
            $table->string('access_token')
                ->nullable();
            $table->timestamp('token_expiry')
                ->nullable();
            $table->string('refresh_token')
                ->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')
                ->unique();
            $table->string('display_name')
                ->unique();
            $table->timestamps();
        });
        Schema::create('role_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                ->unsigned();
            $table->integer('role_id')
                ->unsigned();
            $table->integer('assigned_by')
                ->unsigned()
                ->nullable();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('assigned_by')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
        Schema::create('steam_apps', function (Blueprint $table) {
            $table->integer('id')
                ->unsigned()
                ->unique();
            $table->string('name');
            $table->primary('id');
        });
        Schema::create('steam_user_status_codes', function (Blueprint $table) {
            $table->smallInteger('id')
                ->unsigned()
                ->unique();
            $table->string('name');
            $table->text('display_name');
            $table->primary('id');
        });
        Schema::create('steam_user_app_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                ->unsigned();
            $table->integer('steam_app_id')
                ->unsigned();
            $table->dateTime('start');
            $table->dateTime('end')
                ->nullable();
            $table->timestamps();
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
        Schema::create('steam_user_apps', function (Blueprint $table) {
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
        Schema::create('steam_user_metadata', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                ->unsigned();
            $table->smallInteger('steam_user_status_code_id')
                ->unsigned()
                ->default(0);
            $table->boolean('profile_visible')
                ->nullable();
            $table->boolean('apps_visible')
                ->nullable();
            $table->timestamp('profile_updated_at')
                ->nullable();
            $table->timestamp('apps_updated_at')
                ->nullable();
             $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->foreign('steam_user_status_code_id')
                    ->references('id')
                    ->on('steam_user_status_codes')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
        Schema::create('navigation_links', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->tinyInteger('position');
            $table->text('url')
                ->nullable();
            $table->integer('parent_id')
                ->nullable()
                ->unsigned();
            $table->timestamps();
        });
        Schema::create('lans', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->string('name');
            $table->text('description')
                ->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->boolean('published')
                ->default(false);
            $table->timestamps();
        });
        Schema::create('lan_attendees', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lan_id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
            $table->timestamps();
            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::create('event_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('colour')
                ->nullable();
            $table->timestamps();
        });
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lan_id')
                ->unsigned();
            $table->integer('event_type_id')
                ->unsigned();
            $table->string('name');
            $table->text('description')
                ->nullable();
            $table->boolean('published')
                ->default(false);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->dateTime('signups_open')
                ->nullable();
            $table->dateTime('signups_close')
                ->nullable();
            $table->timestamps();
            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('event_type_id')
                ->references('id')
                ->on('event_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::create('event_signups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
            $table->timestamps();
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::create('guides', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lan_id')
                ->unsigned();
            $table->string('title');
            $table->text('content')
                ->nullable();
            $table->boolean('published')
                ->default(false);
            $table->timestamps();
            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::create('achievements', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->string('name');
            $table->string('description')
                ->nullable();
            $table->timestamps();
        });
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
            $table->integer('achievement_id')
                ->unsigned();
            $table->integer('lan_id')
                ->unsigned();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('achievement_id')
                ->references('id')
                ->on('achievements')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
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
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('guides');
        Schema::dropIfExists('event_signups');
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_types');
        Schema::dropIfExists('lan_attendees');
        Schema::dropIfExists('lans');
        Schema::dropIfExists('navigation_links');
        Schema::dropIfExists('steam_user_metadata');
        Schema::dropIfExists('steam_user_apps');
        Schema::dropIfExists('steam_user_app_sessions');
        Schema::dropIfExists('steam_user_status_codes');
        Schema::dropIfExists('steam_apps');
        Schema::dropIfExists('role_assignments');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_oauth_accounts');
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('logs');

    }
}
