<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('steam_apps', function (Blueprint $table) {
            $table->text('logo_small')
                ->nullable()
                ->default(null);
            $table->text('logo_medium')
                ->nullable()
                ->default(null);
            $table->text('logo_large')
                ->nullable()
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('steam_apps', function (Blueprint $table) {
            $table->dropColumn('logo_small');
            $table->dropColumn('logo_medium');
            $table->dropColumn('logo_large');
        });
    }
};
