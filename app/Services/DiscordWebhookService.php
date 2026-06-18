<?php

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Zeropingheroes\Lanager\Exceptions\DiscordWebhookException;

class DiscordWebhookService
{
    /**
     * Send a message to a Discord webhook.
     *
     * @param  array<int, string>  $imagePaths
     *
     * @throws DiscordWebhookException|ConnectionException
     */
    public function send(string $webhookUrl, string $content, array $imagePaths = []): void
    {
        $response = Http::post($webhookUrl, [
            'content' => $content,
            'flags' => 4, // SUPPRESS_EMBEDS
        ]);

        if ($response->failed()) {
            throw new DiscordWebhookException($response->status(), $response->body());
        }
    }
}
