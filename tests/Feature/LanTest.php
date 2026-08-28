<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Lan;

class LanTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_event_discord_notification_message_can_be_mass_assigned_and_persisted(): void
    {
        $lan = Lan::create([
            'name' => 'My LAN',
            'start' => '2026-06-01 00:00:00',
            'end' => '2026-06-03 00:00:00',
            'default_event_discord_notification_message' => 'Custom LAN default message',
        ]);

        $this->assertDatabaseHas('lans', [
            'id' => $lan->id,
            'default_event_discord_notification_message' => 'Custom LAN default message',
        ]);
    }
}
