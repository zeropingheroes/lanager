<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpgradeDatabase extends Command
{
    /**
     * Set command signature and description
     */
    public function __construct()
    {
        $this->signature = 'lanager:upgrade-database';
        $this->description = __('phrase.upgrade-database');

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->checkDatabaseStructure();

        $this->warn(__('phrase.manually-backup-before-continuing'));
        if (! $this->confirm(__('phrase.are-you-sure'))) {
            die();
        }

        $this->dropTables();
        $this->fixTimestamps();
        $this->upgradeUsers();
        $this->upgradeLans();
        $this->upgradeGuides();
        $this->upgradeEvents();
        $this->upgradeRoles();
        $this->createNewTables();
        $this->spoofInitialMigration();

        $this->call('migrate');
        $this->call('db:seed');
        $this->call('db:seed');
        $this->call('lanager:update-steam-apps');
        $this->call('lanager:update-steam-users', ['--all' => true]);

        if ($this->confirm(__('phrase.confirm-get-app-ownership-data'))) {
            $this->call('lanager:update-steam-user-apps', ['--all' => true]);
        }

        $this->info(__('phrase.successfully-upgraded-database'));

        return;
    }

    /**
     *  Check that the existing database structure matches v0.5.3
     */
    private function checkDatabaseStructure()
    {
        $migrationAlreadyRun = DB::table('migrations')
            ->where('migration', '=', '2018_11_17_205759_release_v1_0_0')
            ->count();

        if ($migrationAlreadyRun) {
            $this->error(__('phrase.database-structure-already-up-to-date'));
            die();
        }

        $expectedTables = [
            'achievements',
            'applications',
            'event_signups',
            'event_types',
            'events',
            'lans',
            'logs',
            'migrations',
            'pages',
            'permissions',
            'roles',
            'servers',
            'sessions',
            'shouts',
            'states',
            'user_achievements',
            'user_roles',
            'users',
        ];

        foreach ($expectedTables as $table) {
            if (!Schema::hasTable($table)) {
                $this->error(__('phrase.database-structure-does-not-match-table-x-missing', ['x' => $table]));
                die();
            }
        }
    }

    /**
     *  Drop tables that are not needed
     */
    private function dropTables()
    {
        $tablesToDrop = [
            'states',
            'servers',
            'event_types',
            'applications',
            'logs',
            'migrations',
            'permissions',
            'sessions',
            'shouts',
        ];

        foreach ($tablesToDrop as $table) {
            $this->info(__('phrase.deleting-x', ['x' => $table]));
            Schema::dropIfExists($table);
        }
    }

    /**
     *  Fix timestamp columns with default zero dates
     */
    private function fixTimestamps()
    {
        $this->info(__('phrase.fixing-timestamp-columns'));

        $tablesWithStandardTimestamps = [
            'achievements',
            'event_signups',
            'pages',
            'roles',
            'user_achievements',
            'user_roles',
            'users'
        ];

        foreach ($tablesWithStandardTimestamps as $table) {
            DB::statement(
                "ALTER TABLE `$table`
            CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL ,
            CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL ;"
            );
        }

        DB::statement(
            "ALTER TABLE `events`
            CHANGE COLUMN `start` `start` TIMESTAMP NULL ,
            CHANGE COLUMN `end` `end` TIMESTAMP NULL ,
            CHANGE COLUMN `signup_opens` `signups_open` TIMESTAMP NULL ,
            CHANGE COLUMN `signup_closes` `signups_close` TIMESTAMP NULL ,
            CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL ,
            CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL ;"
        );

        DB::statement(
            "ALTER TABLE `lans`
            CHANGE COLUMN `start` `start` TIMESTAMP NULL ,
            CHANGE COLUMN `end` `end` TIMESTAMP NULL ,
            CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL ,
            CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL ;"
        );
    }

    /**
     *  Upgrade users table and data
     */
    private function upgradeUsers()
    {
        $this->info(__('phrase.upgrading-x', ['x' => 'users']));

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

        // Create OAuth account for each Steam user
        foreach(DB::table('users')->get() as $user) {
            DB::table('user_oauth_accounts')->insert([
                'user_id' => $user->id,
                'username' => $user->username,
                'provider' => 'steam',
                'provider_id' => $user->steam_id_64,
                'avatar' => $user->avatar,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['steam_id_64', 'avatar', 'ip', 'steam_visibility', 'visible', 'api_key']);
        });
    }

    /**
     *  Upgrade LANs table and data
     */
    private function upgradeLans()
    {
        $this->info(__('phrase.upgrading-x', ['x' => 'LANs']));
        Schema::table('lans', function (Blueprint $table) {
            $table->text('description')
                ->after('name');
            $table->boolean('published')
                ->after('end');
        });
        DB::table('lans')->update(['published' => true]);
    }

    /**
     *  Upgrade guides table and data
     */
    private function upgradeGuides()
    {
        $this->info(__('phrase.upgrading-x', ['x' => 'guides']));

        // Remove hierarchy
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign('pages_parent_id_foreign');
            $table->dropColumn(['parent_id', 'position']);
        });
        Schema::rename('pages', 'guides');

        // Add lan_id field
        Schema::table('guides', function (Blueprint $table) {
            $table->integer('lan_id')
                ->unsigned()
                ->nullable()
                ->after('id');
        });

        // Attach all guides to latest LAN
        DB::table('guides')
            ->update(['lan_id' => $this->getLatestLan()->id]);

        // Make a guide's lan_id required
        Schema::table('guides', function (Blueprint $table) {
            $table->integer('lan_id')
                ->nullable(false)
                ->change();
            }
        );
    }

    /**
     *  Upgrade events table and data
     */
    private function upgradeEvents()
    {
        $this->info(__('phrase.upgrading-x', ['x' => 'events']));
        // Add lan_id field
        Schema::table('events', function (Blueprint $table) {
            $table->integer('lan_id')
                ->unsigned()
                ->nullable()
                ->after('id');
        });

        // Attach all events to latest LAN
        DB::table('events')
            ->update(['lan_id' => $this->getLatestLan()->id]);

        // Make an event's lan_id required
        Schema::table('events', function (Blueprint $table) {
            $table->integer('lan_id')
                ->nullable(false)
                ->change();
        }
        );
    }

    /**
     *  Upgrade roles tables and data
     */
    private function upgradeRoles()
    {
        $this->info(__('phrase.upgrading-x', ['x' => 'roles']));

        // Get role IDs
        $superAdminRole = DB::table('roles')->where('name', '=', 'Super Admin')->first();
        $adminRole = DB::table('roles')->where('name', '=', 'Admin')->first();

        // Set all non-super admins as normal admins
        DB::table('user_roles')
            ->whereNotIn('role_id', [$superAdminRole->id])
            ->update(['role_id' => $adminRole->id]);

        // Remove all roles except admin/super admin
        DB::table('roles')
            ->whereNotIn('name', ['Super Admin', 'Admin'])
            ->delete();
        $roles = DB::table('roles')->get();

        // Add display name to roles table
        Schema::table('roles', function (Blueprint $table) {
            $table->text('display_name')
                ->after('name');
        });

        // Set the name and display name
        foreach($roles as $role) {
            DB::table('roles')
                ->where('id', $role->id)
                ->update([
                    'name' => kebab_case($role->name),
                    'display_name' => $role->name,
                ]);
        }
        Schema::rename('user_roles', 'role_assignments');
    }

    /**
     *  Create new tables
     */
    private function createNewTables()
    {
        $this->info(__('phrase.creating-new-tables'));

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
    }

    /**
     * Get the latest LAN, or create an example LAN
     *
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    private function getLatestLan()
    {
        // If the LANs table is empty, create a LAN
        if (!DB::table('lans')->count()) {
            DB::table('lans')->insert([
                'name' => 'Example LAN',
                'start' => Carbon::parse('next Friday')->addHours(18),
                'end' => Carbon::parse('next Sunday')->addHours(18),
                'published' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Get the latest LAN
        $latestLan = DB::table('lans')
            ->latest('end')
            ->first();
        return $latestLan;
    }

    /**
     *  Spoof the initial migration
     */
    private function spoofInitialMigration()
    {
        $this->call('migrate:install');
        $this->info(__('phrase.spoofing-initial-migration'));
        DB::table('migrations')->insert([
            'migration' => '2018_11_17_205759_release_v1_0_0',
            'batch' => 1,
        ]);
    }
}