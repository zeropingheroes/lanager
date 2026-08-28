<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class DiscordChannelWebhookMessageApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private const string WEBHOOK_URL = 'https://discord.com/api/webhooks/123456789012345678/abcdefghijklmnopqrstuvwxyz_ABCDEF-token';

    private User $adminUser;

    private User $regularUser;

    private Lan $lan;

    private DiscordChannelWebhook $discordChannelWebhook;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->lan = Lan::factory()->create();

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);

        $this->regularUser = User::factory()->create();

        $this->discordChannelWebhook = DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::WEBHOOK_URL,
        ]);
    }

    public function test_send_posts_message_and_returns_success_json(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 204)]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.lans.discord-channel-webhooks.send', [$this->lan, $this->discordChannelWebhook]), [
                'message' => 'Hello from LANager!',
            ]);

        $testResponse->assertOk();
        $testResponse->assertJson(['message' => trans('phrase.discord-message-sent-successfully', ['purpose' => trans('title.'.$this->discordChannelWebhook->purpose)])]);
        Http::assertSent(fn ($req) => $req->url() === self::WEBHOOK_URL);
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_send_returns_error_json_when_discord_api_fails(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(['message' => 'Unknown Webhook'], 404)]);

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.lans.discord-channel-webhooks.send', [$this->lan, $this->discordChannelWebhook]), [
                'message' => 'Hello from LANager!',
            ]);

        $testResponse->assertStatus(502);
        $testResponse->assertJsonStructure(['errors']);
    }

    public function test_send_returns_error_json_when_message_is_missing(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.lans.discord-channel-webhooks.send', [$this->lan, $this->discordChannelWebhook]), []);

        $testResponse->assertStatus(422);
        $testResponse->assertJsonStructure(['errors']);
        Http::assertNothingSent();
    }

    public function test_send_returns_error_json_when_message_exceeds_2000_chars(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.lans.discord-channel-webhooks.send', [$this->lan, $this->discordChannelWebhook]), [
                'message' => str_repeat('a', 2001),
            ]);

        $testResponse->assertStatus(422);
        $testResponse->assertJsonStructure(['errors']);
        Http::assertNothingSent();
    }

    public function test_send_returns_404_when_webhook_belongs_to_different_lan(): void
    {
        Http::fake();
        $otherLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)
            ->postJson(route('api.lans.discord-channel-webhooks.send', [$otherLan, $this->discordChannelWebhook]), [
                'message' => 'Hello!',
            ]);

        $testResponse->assertStatus(404);
        Http::assertNothingSent();
    }

    public function test_send_returns_403_for_non_admin(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->regularUser)
            ->postJson(route('api.lans.discord-channel-webhooks.send', [$this->lan, $this->discordChannelWebhook]), [
                'message' => 'Hello!',
            ]);

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }

    public function test_send_returns_403_for_guest(): void
    {
        Http::fake();

        $testResponse = $this->postJson(route('api.lans.discord-channel-webhooks.send', [$this->lan, $this->discordChannelWebhook]), [
            'message' => 'Hello!',
        ]);

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }
}
