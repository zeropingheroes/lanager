<?php

namespace Tests\Unit;

use Tests\TestCase;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;

class EventDiscordNotificationMessageTest extends TestCase
{
    public function test_content_returns_default_message_when_message_is_blank(): void
    {
        $notification = new EventDiscordNotificationMessage(['message' => null]);

        $this->assertSame(trans('phrase.default-event-discord-notification-message'), $notification->content());
    }

    public function test_content_returns_stored_message_when_present(): void
    {
        $notification = new EventDiscordNotificationMessage(['message' => 'Custom message']);

        $this->assertSame('Custom message', $notification->content());
    }
}
