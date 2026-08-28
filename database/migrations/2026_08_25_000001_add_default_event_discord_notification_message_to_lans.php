<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lans', function (Blueprint $table): void {
            $table->text('default_event_discord_notification_message')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('lans', function (Blueprint $table): void {
            $table->dropColumn('default_event_discord_notification_message');
        });
    }
};
