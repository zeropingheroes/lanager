<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;

class EventDiscordNotificationMessageTest extends TestCase
{
    private function notificationFor(?string $message, ?string $lanDefaultMessage): EventDiscordNotificationMessage
    {
        $lan = new Lan(['default_event_discord_notification_message' => $lanDefaultMessage]);
        $event = (new Event)->setRelation('lan', $lan);

        return new EventDiscordNotificationMessage(['message' => $message])->setRelation('event', $event);
    }

    public function test_content_returns_system_default_message_when_message_and_lan_default_are_both_blank(): void
    {
        $eventDiscordNotificationMessage = $this->notificationFor(message: null, lanDefaultMessage: null);

        $this->assertSame(trans('phrase.default-event-discord-notification-message'), $eventDiscordNotificationMessage->content());
    }

    public function test_content_returns_stored_message_when_present(): void
    {
        $eventDiscordNotificationMessage = $this->notificationFor(message: 'Custom message', lanDefaultMessage: null);

        $this->assertSame('Custom message', $eventDiscordNotificationMessage->content());
    }

    public function test_content_returns_lan_default_message_when_message_is_blank_and_lan_has_one(): void
    {
        $eventDiscordNotificationMessage = $this->notificationFor(message: null, lanDefaultMessage: 'LAN default message');

        $this->assertSame('LAN default message', $eventDiscordNotificationMessage->content());
    }

    public function test_content_returns_stored_message_even_when_lan_has_a_default(): void
    {
        $eventDiscordNotificationMessage = $this->notificationFor(message: 'Custom message', lanDefaultMessage: 'LAN default message');

        $this->assertSame('Custom message', $eventDiscordNotificationMessage->content());
    }
}
