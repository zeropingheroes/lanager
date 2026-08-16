<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class EventDiscordNotificationMessageControllerDispatchTest extends TestCase
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

    public function test_send_posts_message_and_redirects_with_success_on_discord_success(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'It is starting now!',
            'automatic' => true,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.send', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $testResponse->assertSessionHas('success');
        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL && $request->data()['content'] === 'It is starting now!');
        $this->assertFalse($notification->fresh()->automatic);
    }

    public function test_send_redirects_with_error_and_leaves_automatic_unchanged_when_discord_api_fails(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(['message' => 'Unknown Webhook'], 404)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'automatic' => true,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.send', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertTrue($notification->fresh()->automatic);
    }

    public function test_send_redirects_with_error_when_no_live_webhook_configured(): void
    {
        Http::fake();
        EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.send', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        Http::assertNothingSent();
    }

    public function test_send_redirects_with_error_when_no_notification_message_configured(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.send', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        Http::assertNothingSent();
    }

    public function test_send_returns_404_when_event_belongs_to_a_different_lan(): void
    {
        Http::fake();
        $otherLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.send', ['lan' => $otherLan, 'event' => $this->event]));

        $testResponse->assertStatus(404);
        Http::assertNothingSent();
    }

    public function test_send_returns_403_for_non_admin(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->regularUser)
            ->post(route('lans.events.discord-notification-message.send', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }

    // --- preview() ---

    public function test_preview_sends_content_to_test_webhook_and_returns_success_json(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('lans.discord-notification-message.preview', ['lan' => $this->lan]), [
                'content' => 'Preview this please',
            ]);

        $testResponse->assertOk();
        $testResponse->assertJsonStructure(['message']);
        Http::assertSent(fn ($request) => $request->url() === self::TEST_WEBHOOK_URL && $request->data()['content'] === 'Preview this please');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_preview_returns_error_json_when_discord_api_fails(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(['message' => 'Unknown Webhook'], 404)]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('lans.discord-notification-message.preview', ['lan' => $this->lan]), [
                'content' => 'Preview this please',
            ]);

        $testResponse->assertStatus(502);
        $testResponse->assertJsonStructure(['errors']);
    }

    public function test_preview_returns_422_when_content_is_missing(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('lans.discord-notification-message.preview', ['lan' => $this->lan]), []);

        $testResponse->assertStatus(422);
        Http::assertNothingSent();
    }

    public function test_preview_returns_422_when_content_exceeds_2000_chars(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('lans.discord-notification-message.preview', ['lan' => $this->lan]), [
                'content' => str_repeat('a', 2001),
            ]);

        $testResponse->assertStatus(422);
        Http::assertNothingSent();
    }

    public function test_preview_returns_422_when_no_test_webhook_configured(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('lans.discord-notification-message.preview', ['lan' => $this->lan]), [
                'content' => 'Preview this please',
            ]);

        $testResponse->assertStatus(422);
        Http::assertNothingSent();
    }

    public function test_preview_returns_403_for_non_admin(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::TEST_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->regularUser)
            ->postJson(route('lans.discord-notification-message.preview', ['lan' => $this->lan]), [
                'content' => 'Preview this please',
            ]);

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }
}
