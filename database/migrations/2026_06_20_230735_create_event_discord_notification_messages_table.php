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
        Schema::create('event_discord_notification_messages', function (Blueprint $table): void {
            $table->increments('id');
            $table->unsignedInteger('event_id');
            $table->text('message');
            $table->boolean('automatic')->default(true);
            $table->timestamp('automatically_sent_at')->nullable();
            $table->timestamps();

            $table->unique('event_id');

            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_discord_notification_messages');
    }
};
