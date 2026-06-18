<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Zeropingheroes\Lanager\Models\Lan;

class DiscordChannelWebhookFactory extends Factory
{
    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        $webhookId = $this->faker->numerify('####################');
        $webhookToken = $this->faker->regexify('[A-Za-z0-9_-]{68}');

        return [
            'lan_id' => Lan::factory(),
            'purpose' => $this->faker->randomElement(['live', 'test']),
            'webhook_url' => "https://discord.com/api/webhooks/{$webhookId}/{$webhookToken}",
        ];
    }

    /**
     * Set the purpose to "live".
     */
    public function live(): static
    {
        return $this->state(['purpose' => 'live']);
    }

    /**
     * Set the purpose to "test".
     */
    public function test(): static
    {
        return $this->state(['purpose' => 'test']);
    }
}
