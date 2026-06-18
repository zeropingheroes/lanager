<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Http;
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
}
