<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SteamAppIdChangeToBigint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove foreign keys
        Schema::table('steam_user_apps', function (Blueprint $table) {
            $table->dropForeign('steam_user_apps_steam_app_id_foreign');
        });
        Schema::table('steam_user_app_sessions', function (Blueprint $table) {
            $table->dropForeign('steam_user_app_sessions_steam_app_id_foreign');
        });

        // Alter columns
        Schema::table('steam_user_apps', function (Blueprint $table) {
            $table->bigInteger('steam_app_id')->change();
        });
        Schema::table('steam_user_app_sessions', function (Blueprint $table) {
            $table->bigInteger('steam_app_id')->change();
        });
        Schema::table('steam_apps', function (Blueprint $table) {
            $table->bigInteger('id')->change();
        });

        // Re-create foreign keys, now that column types match
        Schema::table('steam_user_apps', function (Blueprint $table) {
            $table->foreign('steam_app_id')
                ->references('id')
                ->on('steam_apps')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::table('steam_user_app_sessions', function (Blueprint $table) {
            $table->foreign('steam_app_id')
                ->references('id')
                ->on('steam_apps')
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
        // Remove foreign keys
        Schema::table('steam_user_apps', function (Blueprint $table) {
            $table->dropForeign('steam_user_apps_steam_app_id_foreign');
        });
        Schema::table('steam_user_app_sessions', function (Blueprint $table) {
            $table->dropForeign('steam_user_app_sessions_steam_app_id_foreign');
        });

        // Alter columns
        Schema::table('steam_user_apps', function (Blueprint $table) {
            $table->integer('steam_app_id')->change();
        });
        Schema::table('steam_user_app_sessions', function (Blueprint $table) {
            $table->integer('steam_app_id')->change();
        });
        Schema::table('steam_apps', function (Blueprint $table) {
            $table->integer('id')->change();
        });

        // Re-create foreign keys, now that column types match
        Schema::table('steam_user_apps', function (Blueprint $table) {
            $table->foreign('steam_app_id')
                ->references('id')
                ->on('steam_apps')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
        Schema::table('steam_user_app_sessions', function (Blueprint $table) {
            $table->foreign('steam_app_id')
                ->references('id')
                ->on('steam_apps')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
