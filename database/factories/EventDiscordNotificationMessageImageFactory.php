<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;

class EventDiscordNotificationMessageImageFactory extends Factory
{
    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'event_discord_notification_message_id' => EventDiscordNotificationMessage::factory(),
            'image_path' => 'images/'.$this->faker->uuid().'.png',
            'sort_order' => 0,
        ];
    }
}
