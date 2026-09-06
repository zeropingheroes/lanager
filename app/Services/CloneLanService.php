<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;

class CloneLanService
{
    /**
     * Create a new LAN cloned from an existing one, along with the selected Guides, Events,
     * Slides and Discord channel webhooks, all inside a single transaction.
     *
     * Any destination field left null falls back to the source LAN's own value. The new LAN,
     * and every cloned Guide/Event/Slide, are always created unpublished.
     *
     * @param  list<int|string>  $guideIds
     * @param  list<int|string>  $eventIds
     * @param  list<int|string>  $slideIds
     * @param  list<int|string>  $webhookIds
     */
    public function clone(
        int $sourceLanId,
        ?string $name = null,
        ?int $venueId = null,
        ?int $achievementId = null,
        ?string $start = null,
        ?string $end = null,
        ?string $defaultEventDiscordNotificationMessage = null,
        array $guideIds = [],
        array $eventIds = [],
        array $slideIds = [],
        array $webhookIds = [],
    ): Lan {
        $sourceLan = Lan::findOrFail($sourceLanId);

        return DB::transaction(function () use (
            $sourceLan,
            $name,
            $venueId,
            $achievementId,
            $start,
            $end,
            $defaultEventDiscordNotificationMessage,
            $guideIds,
            $eventIds,
            $slideIds,
            $webhookIds,
        ): Lan {
            $newLan = Lan::create([
                'name' => $name ?? $sourceLan->name,
                'venue_id' => $venueId ?? $sourceLan->venue_id,
                'achievement_id' => $achievementId ?? $sourceLan->achievement_id,
                'start' => $start ?? $sourceLan->start,
                'end' => $end ?? $sourceLan->end,
                'default_event_discord_notification_message' => $defaultEventDiscordNotificationMessage ?? $sourceLan->default_event_discord_notification_message,
                'published' => false,
            ]);

            $this->cloneGuides($sourceLan, $newLan, $guideIds);
            $this->cloneEvents($sourceLan, $newLan, $eventIds);
            $this->cloneSlides($sourceLan, $newLan, $slideIds);
            $this->cloneDiscordChannelWebhooks($sourceLan, $newLan, $webhookIds);

            return $newLan;
        });
    }

    /**
     * Clone the selected Guides belonging to the source LAN onto the new LAN, unpublished.
     *
     * @param  list<int|string>  $guideIds
     */
    private function cloneGuides(Lan $sourceLan, Lan $newLan, array $guideIds): void
    {
        /** @var iterable<Guide> $guides */
        $guides = $sourceLan->guides()->whereIn('id', $guideIds)->get();

        foreach ($guides as $guide) {
            $this->cloneModel($guide, [
                'lan_id' => $newLan->id,
                'published' => false,
            ]);
        }
    }

    /**
     * Clone the selected Events belonging to the source LAN onto the new LAN, unpublished,
     * with their timing (and any Discord notification message) shifted and cloned alongside them.
     *
     * @param  list<int|string>  $eventIds
     */
    private function cloneEvents(Lan $sourceLan, Lan $newLan, array $eventIds): void
    {
        /** @var iterable<Event> $events */
        $events = $sourceLan->events()
            ->with('discordNotificationMessage.images')
            ->whereIn('id', $eventIds)
            ->get();

        foreach ($events as $event) {
            $newEvent = $this->cloneModel($event, [
                'lan_id' => $newLan->id,
                'published' => false,
                'start' => $this->shiftDate($sourceLan->start, $newLan->start, $event->start),
                'end' => $this->shiftDate($sourceLan->start, $newLan->start, $event->end),
                'signups_open' => $this->shiftDate($sourceLan->start, $newLan->start, $event->signups_open),
                'signups_close' => $this->shiftDate($sourceLan->start, $newLan->start, $event->signups_close),
            ]);

            if ($event->discordNotificationMessage === null) {
                continue;
            }

            $newMessage = $this->cloneModel($event->discordNotificationMessage, [
                'event_id' => $newEvent->id,
                'automatically_sent_at' => null,
            ]);

            foreach ($event->discordNotificationMessage->images as $image) {
                $this->cloneModel($image, [
                    'event_discord_notification_message_id' => $newMessage->id,
                ]);
            }
        }
    }

    /**
     * Clone the selected Slides belonging to the source LAN onto the new LAN, unpublished,
     * with any schedule shifted.
     *
     * @param  list<int|string>  $slideIds
     */
    private function cloneSlides(Lan $sourceLan, Lan $newLan, array $slideIds): void
    {
        /** @var iterable<Slide> $slides */
        $slides = $sourceLan->slides()->whereIn('id', $slideIds)->get();

        foreach ($slides as $slide) {
            $this->cloneModel($slide, [
                'lan_id' => $newLan->id,
                'published' => false,
                'start' => $this->shiftDate($sourceLan->start, $newLan->start, $slide->start),
                'end' => $this->shiftDate($sourceLan->start, $newLan->start, $slide->end),
            ]);
        }
    }

    /**
     * Clone the selected Discord channel webhooks belonging to the source LAN onto the new LAN.
     *
     * @param  list<int|string>  $webhookIds
     */
    private function cloneDiscordChannelWebhooks(Lan $sourceLan, Lan $newLan, array $webhookIds): void
    {
        /** @var iterable<DiscordChannelWebhook> $webhooks */
        $webhooks = $sourceLan->discordChannelWebhooks()->whereIn('id', $webhookIds)->get();

        foreach ($webhooks as $webhook) {
            $this->cloneModel($webhook, [
                'lan_id' => $newLan->id,
            ]);
        }
    }

    /**
     * The given time, moved onto the new LAN's start date by the same number of days it fell
     * after the source LAN's start date, with its time-of-day left unchanged, or null if the
     * given time is null.
     */
    private function shiftDate(Carbon $sourceLanStart, Carbon $newLanStart, ?Carbon $time): ?Carbon
    {
        if ($time === null) {
            return null;
        }

        $dayOffset = $sourceLanStart->copy()->startOfDay()->diffInDays($time->copy()->startOfDay(), false);

        return $newLanStart->copy()->startOfDay()->addDays($dayOffset)
            ->setTime($time->hour, $time->minute, $time->second, $time->microsecond);
    }

    /**
     * A copy of the given model, built from its fillable attributes merged with the given
     * overrides, saved as a new record.
     *
     * @template TModel of Model
     *
     * @param  TModel  $model
     * @param  array<string, mixed>  $overrides
     * @return TModel
     */
    private function cloneModel(Model $model, array $overrides): Model
    {
        $modelClass = $model::class;

        return $modelClass::create(array_merge($model->only($model->getFillable()), $overrides));
    }
}
