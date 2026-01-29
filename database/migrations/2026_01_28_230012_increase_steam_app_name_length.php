<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseSteamAppNameLength extends Migration
{
    /**
     * Run the migrations.
     * @throws Exception
     */
    public function up(): void
    {
        Schema::table('steam_apps', function (Blueprint $table) {
            $table->string('name', 512)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down(): void
    {
        Schema::table('steam_apps', function (Blueprint $table) {
            $table->string('name', 255)->change();
        });
    }
}
