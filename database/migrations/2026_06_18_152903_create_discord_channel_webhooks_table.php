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
        Schema::create('discord_channel_webhooks', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('lan_id');
            $table->string('purpose');
            $table->string('webhook_url', 2048);
            $table->timestamp('created_at')->nullable();

            $table->unique(['lan_id', 'purpose']);

            $table->foreign('lan_id')
                ->references('id')
                ->on('lans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discord_channel_webhooks');
    }
};
