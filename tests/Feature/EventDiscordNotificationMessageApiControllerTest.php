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
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessageImage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class EventDiscordNotificationMessageApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private const string LIVE_WEBHOOK_URL = 'https://discord.com/api/webhooks/123456789012345678/abcdefghijklmnopqrstuvwxyz_ABCDEF-live';

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

    // --- send() ---

    public function test_send_posts_message_and_returns_success_json(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'It is starting now!',
            'automatic' => true,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertJsonStructure(['message']);
        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL && $request->data()['content'] === 'It is starting now!');
        $this->assertFalse($notification->fresh()->automatic);
    }

    public function test_send_substitutes_placeholders_and_leaves_stored_message_raw(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'New event: {{event.name}} - {{event.url}} - {{event.nmae}}',
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertOk();

        $expectedContent = sprintf(
            'New event: %s - %s - {{event.nmae}}',
            $this->event->name,
            route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event])
        );
        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
        $this->assertSame('New event: {{event.name}} - {{event.url}} - {{event.nmae}}', $notification->fresh()->message);
    }

    public function test_send_returns_error_json_and_leaves_automatic_unchanged_when_discord_api_fails(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(['message' => 'Unknown Webhook'], 404)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'automatic' => true,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertStatus(502);
        $testResponse->assertJsonStructure(['errors']);
        $this->assertTrue($notification->fresh()->automatic);
    }

    public function test_send_returns_error_json_when_no_live_webhook_configured(): void
    {
        Http::fake();
        EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertStatus(422);
        $testResponse->assertJsonStructure(['errors']);
        Http::assertNothingSent();
    }

    public function test_send_uses_default_message_when_message_is_blank(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);
        EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => null,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertOk();

        $expectedContent = str_replace(
            ['{{event.name}}', '{{event.url}}'],
            [$this->event->name, route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event])],
            trans('phrase.default-event-discord-notification-message')
        );
        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
    }

    public function test_send_uses_lan_default_message_when_message_is_blank_and_lan_has_one(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);
        $this->lan->update(['default_event_discord_notification_message' => 'LAN default: {{event.name}} - {{event.url}}']);
        EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => null,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertOk();

        $expectedContent = sprintf(
            'LAN default: %s - %s',
            $this->event->name,
            route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event])
        );
        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
    }

    public function test_send_returns_error_json_when_no_notification_message_configured(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertStatus(422);
        $testResponse->assertJsonStructure(['errors']);
        Http::assertNothingSent();
    }

    public function test_send_returns_403_for_non_admin(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->regularUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }

    public function test_send_returns_403_for_guest(): void
    {
        Http::fake();

        $testResponse = $this->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }

    // --- preview() ---

    public function test_preview_sends_content_to_test_webhook_and_returns_success_json(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), [
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
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), [
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
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), []);

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
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), []);

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
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), [
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
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), [
                'message' => str_repeat('a', 2001),
            ]);

        $testResponse->assertStatus(422);
        Http::assertNothingSent();
    }

    public function test_preview_returns_422_when_no_test_webhook_configured(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), [
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
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), [
                'message' => 'Preview this please',
            ]);

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }

    public function test_preview_returns_403_for_guest(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), [
            'message' => 'Preview this please',
        ]);

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }

    // --- image paths ---

    public function test_send_passes_image_paths_to_service_as_multipart(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/event.png', 'fake-png-data');

        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 200)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'Event with image!',
        ]);
        EventDiscordNotificationMessageImage::create([
            'event_discord_notification_message_id' => $notification->id,
            'image_path' => 'images/event.png',
            'sort_order' => 0,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.send', ['event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertJsonStructure(['message']);
        Http::assertSent(function ($request): bool {
            $contentType = $request->header('Content-Type')[0] ?? '';

            return $request->url() === self::LIVE_WEBHOOK_URL
                && str_contains($contentType, 'multipart/form-data')
                && str_contains((string) $request->body(), 'event.png');
        });
    }

    public function test_preview_passes_image_paths_to_service_as_multipart(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/preview.png', 'fake-png-data');

        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 200)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.events.discord-notification-message.preview', ['event' => $this->event]), [
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
