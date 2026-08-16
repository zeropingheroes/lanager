<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Throwable;
use Zeropingheroes\Lanager\Exceptions\DiscordWebhookException;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Services\DiscordWebhookService;

class SendDiscordEventNotificationMessages extends Command
{
    /**
     * Set command signature and description.
     */
    public function __construct()
    {
        $this->signature = 'lanager:send-discord-event-notification-messages';
        $this->description = trans('phrase.send-due-discord-event-notifications');

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $events = Event::query()
            ->whereBetween('start', [now()->subSeconds(60), now()])
            ->where('published', true)
            ->whereHas('discordNotificationMessage', function ($query): void {
                $query->where('automatic', true);
            })
            ->whereHas('lan.discordChannelWebhooks', function ($query): void {
                $query->where('purpose', 'live');
            })
            ->with(['discordNotificationMessage', 'lan.discordChannelWebhooks'])
            ->get();

        $eventsProcessed = 0;
        $eventsSkipped = 0;
        $eventsFailed = 0;

        foreach ($events as $event) {
            try {
                $notification = $event->discordNotificationMessage;

                if ($notification === null || ! $notification->automatic) {
                    $eventsSkipped++;

                    continue;
                }

                if ($notification->automatically_sent_at !== null &&
                    $notification->automatically_sent_at->greaterThanOrEqualTo($event->start)
                ) {
                    $eventsSkipped++;

                    continue;
                }

                $liveWebhook = $event->lan->discordChannelWebhooks->firstWhere('purpose', 'live');

                if ($liveWebhook === null) {
                    $eventsSkipped++;

                    continue;
                }

                try {
                    (new DiscordWebhookService)->send($liveWebhook->webhook_url, $notification->message);
                } catch (DiscordWebhookException|ConnectionException $exception) {
                    $message = trans('phrase.failed-to-send-discord-event-notification-message');
                    $this->error($message);
                    Log::error($message, [
                        'event_id' => $event->id,
                        'lan_id' => $event->lan_id,
                        'purpose' => 'live',
                        'http_status' => $exception instanceof DiscordWebhookException ? $exception->httpStatus : null,
                        'error' => $exception instanceof DiscordWebhookException ? $exception->errorBody : $exception->getMessage(),
                    ]);

                    $eventsFailed++;

                    continue;
                }

                $notification->update(['automatically_sent_at' => now()]);

                $message = trans('phrase.discord-event-notification-message-sent');
                $this->info($message);
                Log::info($message, [
                    'event_id' => $event->id,
                    'lan_id' => $event->lan_id,
                    'purpose' => 'live',
                    'result' => 'success',
                ]);

                $eventsProcessed++;
            } catch (Throwable $throwable) {
                $message = trans('phrase.unexpected-error-sending-discord-event-notification-message');
                $this->error($message);
                Log::error($message, [
                    'event_id' => $event->id,
                    'error' => $throwable->getMessage(),
                ]);

                $eventsFailed++;
            }
        }

        $message = trans('phrase.discord-event-notification-messages-run-completed', [
            'processed' => $eventsProcessed,
            'skipped' => $eventsSkipped,
            'failed' => $eventsFailed,
        ]);
        $this->info($message);
        Log::info($message);

        return self::SUCCESS;
    }
}
