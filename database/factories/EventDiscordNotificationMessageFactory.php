<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zeropingheroes\Lanager\Models\Event;

class EventDiscordNotificationMessageFactory extends Factory
{
    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'message' => $this->faker->sentence(),
            'automatic' => true,
            'automatically_sent_at' => null,
        ];
    }

    /**
     * Mark the notification as not enabled for automatic dispatch.
     */
    public function notAutomatic(): static
    {
        return $this->state(['automatic' => false]);
    }

    /**
     * Mark the notification as already automatically sent.
     */
    public function sent(): static
    {
        return $this->state(['automatically_sent_at' => now()]);
    }
}
