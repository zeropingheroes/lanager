<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSteamAppType extends Migration
{
    /**
     * Run the migrations.
     * @throws Exception
     */
    public function up(): void
    {
        Schema::table('steam_apps', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down(): void
    {
        Schema::table('steam_apps', function (Blueprint $table) {
            $table->string('type', 32)
                ->nullable()
                ->default(null);
        });
    }
}
