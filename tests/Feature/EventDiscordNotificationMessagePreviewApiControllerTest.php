<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class EventDiscordNotificationMessagePreviewApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private const string TEST_WEBHOOK_URL = 'https://discord.com/api/webhooks/123456789012345678/abcdefghijklmnopqrstuvwxyz_ABCDEF-test';

    private User $adminUser;

    private User $regularUser;

    private Lan $lan;

    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->lan = Lan::factory()->create();

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);

        $this->regularUser = User::factory()->create();

        $this->event = Event::factory()->create([
            'lan_id' => $this->lan->id,
            'start' => $this->lan->start,
            'end' => $this->lan->end,
        ]);
    }

    // --- store() ---

    public function test_preview_sends_content_to_test_webhook_and_returns_success_json(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Preview this please',
            ]);

        $testResponse->assertOk();
        $testResponse->assertJsonStructure(['message']);
        Http::assertSent(fn ($request) => $request->url() === self::TEST_WEBHOOK_URL && $request->data()['content'] === 'Preview this please');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_preview_substitutes_placeholders(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'New event: {{event.name}} - {{event.url}} - {{event.nmae}}',
            ]);

        $testResponse->assertOk();

        $expectedContent = sprintf(
            'New event: %s - %s - {{event.nmae}}',
            $this->event->name,
            route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event])
        );
        Http::assertSent(fn ($request) => $request->url() === self::TEST_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
    }

    public function test_preview_uses_default_message_when_content_is_missing(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), []);

        $testResponse->assertOk();

        $expectedContent = str_replace(
            ['{{event.name}}', '{{event.url}}'],
            [$this->event->name, route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event])],
            trans('phrase.default-event-discord-notification-message')
        );
        Http::assertSent(fn ($request) => $request->url() === self::TEST_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
    }

    public function test_preview_uses_lan_default_message_when_content_is_missing_and_lan_has_one(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);
        $this->lan->update(['default_event_discord_notification_message' => 'LAN default: {{event.name}} - {{event.url}}']);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), []);

        $testResponse->assertOk();

        $expectedContent = sprintf(
            'LAN default: %s - %s',
            $this->event->name,
            route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event])
        );
        Http::assertSent(fn ($request) => $request->url() === self::TEST_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
    }

    public function test_preview_returns_error_json_when_discord_api_fails(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(['message' => 'Unknown Webhook'], 404)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Preview this please',
            ]);

        $testResponse->assertStatus(502);
        $testResponse->assertJsonStructure(['errors']);
    }

    public function test_preview_returns_422_when_content_exceeds_2000_chars(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => str_repeat('a', 2001),
            ]);

        $testResponse->assertStatus(422);
        Http::assertNothingSent();
    }

    public function test_preview_returns_422_when_no_test_webhook_configured(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Preview this please',
            ]);

        $testResponse->assertStatus(422);
        Http::assertNothingSent();
    }

    public function test_preview_returns_403_for_non_admin(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->regularUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Preview this please',
            ]);

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }

    public function test_preview_returns_401_for_guest(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
            'message' => 'Preview this please',
        ]);

        $testResponse->assertStatus(401);
        Http::assertNothingSent();
    }

    // --- image paths ---

    public function test_preview_passes_image_paths_to_service_as_multipart(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/preview.png', 'fake-png-data');

        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 200)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Preview with image',
                'image_paths' => ['images/preview.png'],
            ]);

        $testResponse->assertOk();
        Http::assertSent(function ($request): bool {
            $contentType = $request->header('Content-Type')[0] ?? '';

            return $request->url() === self::TEST_WEBHOOK_URL
                && str_contains($contentType, 'multipart/form-data')
                && str_contains((string) $request->body(), 'preview.png');
        });
    }
}
