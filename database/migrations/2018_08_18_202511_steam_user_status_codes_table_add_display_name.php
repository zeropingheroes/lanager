<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Zeropingheroes\Lanager\SteamUserStatusCode;

class SteamUserStatusCodesTableAddDisplayName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('steam_user_status_codes', function (Blueprint $table) {
            $table->text('display_name')
                ->after('name');
        });

        // If there are existing steam_user_status_codes in the database, generate the name from display name
        if (SteamUserStatusCode::count()) {
            $statusCodes = SteamUserStatusCode::all();
            foreach($statusCodes as $statusCode) {
                $statusCode->display_name = $statusCode->name;
                $statusCode->name = kebab_case($statusCode->name);
                $statusCode->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('steam_user_status_codes', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });

        // If there are existing steam_user_status_codes in the database, revert to the old name
        if (SteamUserStatusCode::count()) {
            $statusCodes = SteamUserStatusCode::all();
            foreach($statusCodes as $statusCode) {
                $statusCode->name = str_replace('-', ' ', title_case($statusCode->name));
                $statusCode->save();
            }
        }
    }
}
