<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('discord_notify')->default(false)->after('published');
            $table->text('discord_message')->nullable()->after('discord_notify');
            $table->timestamp('discord_notified_at')->nullable()->after('discord_message');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['discord_notify', 'discord_message', 'discord_notified_at']);
        });
    }
};
