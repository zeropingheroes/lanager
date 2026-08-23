<?php

namespace Zeropingheroes\Lanager\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Zeropingheroes\Lanager\Exceptions\DiscordWebhookException;

class DiscordWebhookService
{
    public const int MAX_IMAGES = 10;

    public const int MAX_FILE_BYTES = 10_485_760; // 10 MB

    public const int MAX_TOTAL_BYTES = 26_214_400; // 25 MB

    /** @var array<int, string> */
    public const array PERMITTED_IMAGE_EXTENSIONS = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

    /**
     * Replace `{{...}}` placeholders in $message with the values in $variables.
     *
     * Placeholders not present in $variables are left unchanged.
     *
     * @param  array<string, string>  $variables  Map of `{{placeholder}}` => replacement value
     */
    public function resolvePlaceholders(string $message, array $variables): string
    {
        return strtr($message, $variables);
    }

    /**
     * Send a message to a Discord webhook.
     *
     * When $imagePaths is non-empty the request is sent as multipart form data with a
     * payload_json part and one files[N] part per image. Missing image files are skipped.
     * When $imagePaths is empty the request is sent as JSON (unchanged behaviour).
     *
     * @param  array<int, string>  $imagePaths  Paths relative to the public disk (e.g. images/foo.png)
     *
     * @throws DiscordWebhookException|ConnectionException
     */
    public function send(string $webhookUrl, string $content, array $imagePaths = []): void
    {
        if ($imagePaths !== []) {
            $response = $this->sendMultipart($webhookUrl, $content, $imagePaths);
        } else {
            $response = Http::post($webhookUrl, [
                'content' => $content,
                'flags' => 4, // SUPPRESS_EMBEDS
            ]);
        }

        if ($response->failed()) {
            throw new DiscordWebhookException($response->status(), $response->body());
        }
    }

    /**
     * @param  array<int, string>  $imagePaths
     */
    private function sendMultipart(string $webhookUrl, string $content, array $imagePaths): Response
    {
        $payloadJson = json_encode(['content' => $content, 'flags' => 4]);

        /** @var PendingRequest $request */
        $request = Http::attach(
            'payload_json',
            $payloadJson !== false ? $payloadJson : '{}',
            null,
            ['Content-Type' => 'application/json']
        );

        $index = 0;
        foreach ($imagePaths as $imagePath) {
            if (! Storage::disk('public')->exists($imagePath)) {
                continue;
            }

            $absolutePath = Storage::disk('public')->path($imagePath);
            $contents = file_get_contents($absolutePath);

            if ($contents === false) {
                continue;
            }

            $request = $request->attach("files[{$index}]", $contents, basename($imagePath));
            $index++;
        }

        return $request->post($webhookUrl);
    }
}
