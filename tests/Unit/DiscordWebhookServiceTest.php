<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Zeropingheroes\Lanager\Exceptions\DiscordWebhookException;
use Zeropingheroes\Lanager\Services\DiscordWebhookService;

class DiscordWebhookServiceTest extends TestCase
{
    private const string WEBHOOK_URL = 'https://discord.com/api/webhooks/123456789/abcdef_token';

    private const string CONTENT = 'Hello, Discord!';

    public function test_does_not_throw_on_successful_send(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 204)]);

        (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT);

        $this->addToAssertionCount(1);
    }

    public function test_sends_content_in_json_payload(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 204)]);

        (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT);

        Http::assertSent(fn ($request) => $request->data()['content'] === self::CONTENT);
    }

    public function test_always_sends_flags_4_in_payload(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 204)]);

        (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT);

        Http::assertSent(fn ($request) => $request->data()['flags'] === 4);
    }

    public function test_throws_discord_webhook_exception_on_non_2xx(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(['message' => 'Unknown Webhook'], 404)]);

        try {
            (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT);
            $this->fail('Expected DiscordWebhookException to be thrown');
        } catch (DiscordWebhookException $discordWebhookException) {
            $this->assertSame(404, $discordWebhookException->httpStatus);
            $this->assertIsString($discordWebhookException->errorBody);
        }
    }

    public function test_sends_multipart_when_image_paths_provided(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        Storage::fake('public');
        Storage::disk('public')->put('images/test.png', 'fake-png-data');

        (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT, ['images/test.png']);

        Http::assertSent(function ($request): bool {
            $contentType = $request->header('Content-Type')[0] ?? '';

            return str_contains($contentType, 'multipart/form-data');
        });
    }

    public function test_multipart_payload_json_contains_correct_content_and_flags(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        Storage::fake('public');
        Storage::disk('public')->put('images/test.png', 'fake-png-data');

        (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT, ['images/test.png']);

        Http::assertSent(function ($request): bool {
            $body = $request->body();
            $expectedJson = json_encode(['content' => self::CONTENT, 'flags' => 4]);

            return str_contains($body, $expectedJson !== false ? $expectedJson : '');
        });
    }

    public function test_multipart_includes_file_parts_in_order(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        Storage::fake('public');
        Storage::disk('public')->put('images/alpha.png', 'alpha-data');
        Storage::disk('public')->put('images/beta.png', 'beta-data');

        (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT, [
            'images/alpha.png',
            'images/beta.png',
        ]);

        Http::assertSent(function ($request): bool {
            $body = $request->body();

            $alphaPos = strpos($body, 'alpha.png');
            $betaPos = strpos($body, 'beta.png');

            return $alphaPos !== false && $betaPos !== false && $alphaPos < $betaPos;
        });
    }

    public function test_missing_image_files_are_silently_skipped(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 200)]);
        Storage::fake('public');
        Storage::disk('public')->put('images/exists.png', 'real-data');

        (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT, [
            'images/missing.png',
            'images/exists.png',
        ]);

        Http::assertSent(function ($request): bool {
            $body = $request->body();

            return ! str_contains($body, 'missing.png') && str_contains($body, 'exists.png');
        });
    }

    public function test_text_only_send_unchanged_when_no_image_paths_provided(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 204)]);

        (new DiscordWebhookService)->send(self::WEBHOOK_URL, self::CONTENT, []);

        Http::assertSent(function ($request): bool {
            $contentType = $request->header('Content-Type')[0] ?? '';

            return str_contains($contentType, 'application/json');
        });
    }

    public function test_resolve_placeholders_replaces_known_placeholders(): void
    {
        $result = (new DiscordWebhookService)->resolvePlaceholders(
            'New event: {{event.name}} ({{event.url}})',
            ['{{event.name}}' => 'Summer LAN 2026', '{{event.url}}' => 'https://example.com/events/1']
        );

        $this->assertSame('New event: Summer LAN 2026 (https://example.com/events/1)', $result);
    }

    public function test_resolve_placeholders_leaves_unrecognized_placeholder_literal(): void
    {
        $result = (new DiscordWebhookService)->resolvePlaceholders(
            'New event: {{event.nmae}}',
            ['{{event.name}}' => 'Summer LAN 2026']
        );

        $this->assertSame('New event: {{event.nmae}}', $result);
    }

    public function test_resolve_placeholders_leaves_message_unchanged_when_no_placeholders_present(): void
    {
        $result = (new DiscordWebhookService)->resolvePlaceholders(self::CONTENT, ['{{event.name}}' => 'Summer LAN 2026']);

        $this->assertSame(self::CONTENT, $result);
    }
}
