<?php

use Illuminate\Database\Migrations\Migration;

class ReleaseV053 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Skip this migration if the database structure has already been created
        // by previous versions of LANager's non-consolidated migration files
        if(DB::table('migrations')->count() != 0) {
            return;
        }

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('username', 32);
            $table->string('steam_id_64', 17)
                ->unique();
            $table->integer('steam_visibility')
                ->default(1)
                ->unsigned();
            $table->string('ip', 15);
            $table->string('avatar', 255);
            $table->integer('visible')
                ->default(1)
                ->unsigned();
            $table->string('api_key', 32);
            $table->string('remember_token', 100);
            $table->timestamps();
        });
        Schema::create('achievements', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
        Schema::create('applications', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('steam_app_id')
                ->unique();
            $table->timestamps();
        });
        Schema::create('event_types', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('colour')
                ->nullable();
            $table->timestamps();
        });
        Schema::create('lans', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->timestamps();
        });
        Schema::create('logs', function ($table) {
            $table->increments('id');
            $table->string('php_sapi_name');
            $table->string('level');
            $table->text('message');
            $table->text('context');
            $table->timestamp('created_at');
        });
        Schema::create('permissions', function ($table) {
            $table->increments('id');
            $table->integer('user_id')
                ->unsigned();
            $table->string('type');
            $table->string('action');
            $table->string('resource');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::create('roles', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('events', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')
                ->nullable();
            $table->timestamp('start');
            $table->timestamp('end');
            $table->timestamp('signup_opens')
                ->nullable();
            $table->timestamp('signup_closes')
                ->nullable();
            $table->integer('event_type_id')
                ->unsigned();
            $table->integer('published')
                ->default(1)
                ->unsigned();
            $table->timestamps();
            $table->foreign('event_type_id')
                ->references('id')
                ->on('event_types');
        });
        Schema::create('event_signups', function ($table) {
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
        Schema::create('pages', function ($table) {
            $table->increments('id');
            $table->integer('parent_id')
                ->nullable()
                ->unsigned();
            $table->string('title');
            $table->text('content')
                ->nullable();
            $table->integer('position')
                ->unsigned()
                ->nullable();
            $table->integer('published')
                ->default(1)
                ->unsigned();
            $table->timestamps();
            $table->foreign('parent_id')
                ->references('id')
                ->on('pages')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
        Schema::create('servers', function ($table) {
            $table->increments('id');
            $table->integer('application_id')
                ->unsigned();
            $table->string('name')
                ->nullable();
            $table->string('address');    // ipv4, hostname or ipv6
            $table->string('port');
            $table->boolean('pinned');
            $table->timestamps();
            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::create('sessions', function ($table) {
            $table->string('id')
                ->unique();
            $table->text('payload');
            $table->integer('last_activity');
        });
        Schema::create('shouts', function ($table) {
            $table->increments('id');
            $table->integer('user_id')
                ->unsigned();
            $table->string('content');
            $table->boolean('pinned');
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::create('user_roles', function ($table) {
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
        Schema::create('user_achievements', function ($table) {
            $table->increments('id')
                ->unsigned();
            $table->integer('user_id')
                ->unsigned();
            $table->integer('achievement_id')
                ->unsigned();
            $table->integer('lan_id')
                ->unsigned()
                ->nullable();
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
                ->onDelete('set null');
        });
        Schema::create('states', function ($table) {
            $table->increments('id');
            $table->integer('user_id')
                ->unsigned();
            $table->integer('application_id')
                ->unsigned()
                ->nullable(); // nullable to prevent circular dependencies
            $table->integer('server_id')
                ->unsigned()
                ->nullable();  // nullable to prevent circular dependencies
            $table->smallInteger('status')
                ->unsigned();
            $table->timestamps();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->foreign('server_id')
                ->references('id')
                ->on('servers')
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->index(['user_id', 'created_at']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Skip dropping all tables if the database structure was created
        // by previous versions of LANager's non-consolidated migration files
        // rather than this consolidated migration
        if(DB::table('migrations')->count() != 1) {
            return;
        }

        Schema::drop('states');
        Schema::drop('user_achievements');
        Schema::drop('user_roles');
        Schema::drop('shouts');
        Schema::drop('sessions');
        Schema::drop('servers');
        Schema::drop('pages');
        Schema::drop('event_signups');
        Schema::drop('events');
        Schema::drop('roles');
        Schema::drop('permissions');
        Schema::drop('logs');
        Schema::drop('lans');
        Schema::drop('event_types');
        Schema::drop('applications');
        Schema::drop('achievements');
        Schema::drop('users');
    }

}
