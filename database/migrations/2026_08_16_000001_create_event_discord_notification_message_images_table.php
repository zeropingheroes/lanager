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
        Schema::create('event_discord_notification_message_images', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('event_discord_notification_message_id');
            $table->string('image_path', 2048);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamp('created_at')->nullable();

            $table->foreign('event_discord_notification_message_id', 'event_discord_notification_message_images_msg_id_foreign')
                ->references('id')
                ->on('event_discord_notification_messages')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_discord_notification_message_images');
    }
};
