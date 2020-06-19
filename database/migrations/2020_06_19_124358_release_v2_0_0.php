<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReleaseV200 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        $migratingFromV1_1_3 = DB::table('migrations')->where(
            'migration',
            '=',
            '2020_06_08_150547_lan_attendee_game_picks_table_drop'
        )->count();

        if ($migratingFromV1_1_3) {
            // Clear migrations table
            DB::table('migrations')->truncate();

            // As the database is already up-to-date with this migration
            // do not run this migration
            return;
        }

        // If the migration table is not empty, it means the last migration that was run was not
        // the most recent migration before the migrations were squashed into this migration
        if (DB::table('migrations')->count()) {
            // throw exception:
            // please git switch to v1.1.3 and run migrate to migrate your existing data
            // or run migrate:fresh to disgard existing data
            throw new Exception(
                'Unable to automatically upgrade your database. ' .
                'To retain your existing data run: \'git checkout tags/v1.1.3\', ' .
                '\'php artisan migrate\', ' .
                '\'git checkout master\'. ' .
                'Alternatively to upgrade your database but clear existing data, run: ' .
                '\'php artisan migrate:fresh\' '
            );
        }

        Schema::create(
            'phpdebugbar',
            function (Blueprint $table) {
                $table->string('id');
                $table->longText('data');
                $table->string('meta_utime');
                $table->dateTime('meta_datetime');
                $table->text('meta_uri');
                $table->string('meta_ip');
                $table->string('meta_method');

                $table->primary('id');
                $table->index('meta_utime');
                $table->index('meta_datetime');
                $table->index('meta_ip');
                $table->index('meta_method');
            }
        );
        Schema::create(
            'sessions',
            function (Blueprint $table) {
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
            }
        );
        Schema::create(
            'whitelisted_ip_ranges',
            function (Blueprint $table) {
                $table->increments('id')
                    ->unsigned();
                $table->string('ip_range');
                $table->string('description')
                    ->nullable();
                $table->timestamps();
            }
        );
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('username');
                $table->string('api_token', 80)
                    ->unique()
                    ->nullable();
                $table->rememberToken();
                $table->timestamps();
            }
        );
        Schema::create(
            'user_oauth_accounts',
            function (Blueprint $table) {
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
            }
        );
        Schema::create(
            'roles',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')
                    ->unique();
                $table->string('display_name')
                    ->unique();
                $table->timestamps();
            }
        );
        Schema::create(
            'role_assignments',
            function (Blueprint $table) {
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
            }
        );
        Schema::create(
            'steam_apps',
            function (Blueprint $table) {
                $table->bigInteger('id')
                    ->unsigned()
                    ->unique();
                $table->string('name');
                $table->string('type', 32)
                    ->nullable()
                    ->default(null);
                $table->primary('id');
            }
        );
        Schema::create(
            'steam_user_status_codes',
            function (Blueprint $table) {
                $table->smallInteger('id')
                    ->unsigned()
                    ->unique();
                $table->string('name');
                $table->text('display_name');
                $table->primary('id');
            }
        );
        Schema::create(
            'steam_user_app_sessions',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')
                    ->unsigned();
                $table->bigInteger('steam_app_id')
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
            }
        );
        Schema::create(
            'steam_user_apps',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')
                    ->unsigned();
                $table->bigInteger('steam_app_id')
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
            }
        );
        Schema::create(
            'steam_user_metadata',
            function (Blueprint $table) {
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
            }
        );
        Schema::create(
            'achievements',
            function (Blueprint $table) {
                $table->increments('id')
                    ->unsigned();
                $table->string('name');
                $table->string('description')
                    ->nullable();
                $table->string('image_filename')
                    ->nullable();
                $table->timestamps();
            }
        );
        Schema::create(
            'navigation_links',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->tinyInteger('position');
                $table->text('url')
                    ->nullable();
                $table->integer('parent_id')
                    ->nullable()
                    ->unsigned();
                $table->timestamps();
            }
        );
        Schema::create(
            'venues',
            function (Blueprint $table) {
                $table->increments('id')
                    ->unsigned();
                $table->string('name')
                    ->unique();
                $table->string('street_address')
                    ->unique();
                $table->text('description')
                    ->nullable();
                $table->timestamps();
            }
        );
        Schema::create(
            'lans',
            function (Blueprint $table) {
                $table->increments('id')
                    ->unsigned();
                $table->integer('venue_id')
                    ->unsigned()
                    ->nullable();
                $table->integer('achievement_id')
                    ->unsigned()
                    ->nullable();
                $table->string('name');
                $table->text('description')
                    ->nullable();
                $table->dateTime('start');
                $table->dateTime('end');
                $table->boolean('published')
                    ->default(false);
                $table->timestamps();
                $table->foreign('venue_id')
                    ->references('id')
                    ->on('venues')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
                $table->foreign('achievement_id')
                    ->references('id')
                    ->on('achievements')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
            }
        );
        Schema::create(
            'lan_attendees',
            function (Blueprint $table) {
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
            }
        );
        Schema::create(
            'events',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('lan_id')
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
            }
        );
        Schema::create(
            'event_signups',
            function (Blueprint $table) {
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
            }
        );
        Schema::create(
            'guides',
            function (Blueprint $table) {
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
            }
        );
        Schema::create(
            'slides',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('lan_id')
                    ->unsigned();
                $table->string('name');
                $table->text('content');
                $table->tinyInteger('position');
                $table->smallInteger('duration');
                $table->boolean('published')
                    ->default(false);
                $table->timestamp('start')
                    ->nullable();
                $table->timestamp('end')
                    ->nullable();
                $table->timestamps();
                $table->foreign('lan_id')
                    ->references('id')
                    ->on('lans')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        );
        Schema::create(
            'user_achievements',
            function (Blueprint $table) {
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
            }
        );
        Schema::create(
            'lan_games',
            function (Blueprint $table) {
                $table->increments('id')
                    ->unsigned();
                $table->integer('lan_id')
                    ->unsigned();
                $table->string('game_name');
                $table->integer('created_by')
                    ->unsigned();
                $table->timestamps();

                $table->foreign('lan_id')
                    ->references('id')
                    ->on('lans')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->foreign('created_by')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        );
        Schema::create(
            'lan_game_votes',
            function (Blueprint $table) {
                $table->increments('id')
                    ->unsigned();
                $table->integer('lan_game_id')
                    ->unsigned();
                $table->integer('user_id')
                    ->unsigned();
                $table->timestamps();

                $table->foreign('lan_game_id')
                    ->references('id')
                    ->on('lan_games')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lan_game_votes');
        Schema::dropIfExists('lan_games');
        Schema::dropIfExists('user_achievements');
        Schema::dropIfExists('slides');
        Schema::dropIfExists('guides');
        Schema::dropIfExists('event_signups');
        Schema::dropIfExists('events');
        Schema::dropIfExists('lan_attendees');
        Schema::dropIfExists('lans');
        Schema::dropIfExists('venues');
        Schema::dropIfExists('navigation_links');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('steam_user_metadata');
        Schema::dropIfExists('steam_user_apps');
        Schema::dropIfExists('steam_user_app_sessions');
        Schema::dropIfExists('steam_user_status_codes');
        Schema::dropIfExists('steam_apps');
        Schema::dropIfExists('role_assignments');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_oauth_accounts');
        Schema::dropIfExists('users');
        Schema::dropIfExists('whitelisted_ip_ranges');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('phpdebugbar');
    }
}
