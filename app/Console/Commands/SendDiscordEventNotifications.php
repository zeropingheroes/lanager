<?php

namespace Zeropingheroes\Lanager\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Models\Event;

class SendDiscordEventNotifications extends Command
{
    public function __construct()
    {
        $this->signature = 'lanager:send-discord-event-notifications';
        $this->description = 'Send Discord notifications for events that have just started';

        parent::__construct();
    }

    public function handle(): int
    {
        $events = Event::where('discord_notify', true)
            ->where('published', true)
            ->whereNotNull('discord_message')
            ->where('start', '<=', now())
            ->whereNull('discord_notified_at')
            ->whereHas('lan', fn ($q) => $q->where('published', true)->whereNotNull('discord_webhook_url'))
            ->with('lan')
            ->get();

        if ($events->isEmpty()) {
            return 0;
        }

        $failed = 0;

        foreach ($events as $event) {
            $webhookUrl = $event->lan->discord_webhook_url;

            $response = Http::asJson()->post($webhookUrl, ['content' => $event->discord_message]);

            if ($response->successful()) {
                $event->update(['discord_notified_at' => now()]);

                $message = "Sent Discord notification for event #{$event->id} \"{$event->name}\"";
                Log::info($message);
                $this->info($message);
            } else {
                $failed++;
                $message = "Failed to send Discord notification for event #{$event->id} \"{$event->name}\": HTTP {$response->status()}";
                Log::error($message, ['discord_response' => $response->json()]);
                $this->error($message);
            }
        }

        return $failed > 0 ? 1 : 0;
    }
}
