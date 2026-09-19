<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Support\Facades\DB;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;

class CloneEventService
{
    /**
     * Create an Event cloned from $sourceEvent, and optionally:
     * - Override event field values using $overrides.
     * - Create, clone, or skip the event's Discord notification message
     */
    public function clone(
        Event $sourceEvent,
        array $overrides = [],
        ?string $discordNotificationMessageOption = null
    ): Event {
        return DB::transaction(function () use ($sourceEvent, $overrides, $discordNotificationMessageOption): Event {
            $newEvent = Event::create(array_merge(
                $sourceEvent->only($sourceEvent->getFillable()),
                $overrides,
            ));

            $this->cloneDiscordNotificationMessage($sourceEvent, $newEvent, $discordNotificationMessageOption);

            return $newEvent;
        });
    }

    /**
     * Create $newEvent's Discord notification message according to $discordNotificationMessageOption
     * - Clone $sourceEvent's Discord notification message ('clone_existing')
     * - Create the default Discord notification message ('create_default')
     * - Don't create a Discord notification message (null)
     */
    private function cloneDiscordNotificationMessage(
        Event $sourceEvent,
        Event $newEvent,
        ?string $discordNotificationMessageOption
    ): void {
        if ($discordNotificationMessageOption === 'clone_existing' && $sourceEvent->discordNotificationMessage !== null) {
            $sourceMessage = $sourceEvent->discordNotificationMessage;

            $newMessage = EventDiscordNotificationMessage::create(array_merge(
                $sourceMessage->only($sourceMessage->getFillable()),
                [
                    'event_id' => $newEvent->id,
                    'automatically_sent_at' => null,
                ],
            ));

            foreach ($sourceMessage->images as $image) {
                $imageClass = $image::class;

                $imageClass::create(array_merge(
                    $image->only($image->getFillable()),
                    ['event_discord_notification_message_id' => $newMessage->id],
                ));
            }
        } elseif ($discordNotificationMessageOption === 'create_default') {
            EventDiscordNotificationMessage::create([
                'event_id' => $newEvent->id,
                'message' => null,
            ]);
        }
    }
}
