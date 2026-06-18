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

class DiscordChannelWebhookMessageControllerTest extends TestCase
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

    public function test_create_returns_compose_form_for_admin(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.discord-channel-webhooks.messages.create', [$this->lan, $this->discordChannelWebhook]));

        $testResponse->assertStatus(200);
        $testResponse->assertViewIs('pages.discord-channel-webhook-messages.create');
    }

    public function test_create_returns_404_when_webhook_belongs_to_different_lan(): void
    {
        $otherLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.discord-channel-webhooks.messages.create', [$otherLan, $this->discordChannelWebhook]));

        $testResponse->assertStatus(404);
    }

    public function test_create_returns_403_for_non_admin(): void
    {
        $testResponse = $this->actingAs($this->regularUser)
            ->get(route('lans.discord-channel-webhooks.messages.create', [$this->lan, $this->discordChannelWebhook]));

        $testResponse->assertStatus(403);
    }

    public function test_store_sends_message_and_redirects_with_success_on_discord_success(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(null, 204)]);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.messages.store', [$this->lan, $this->discordChannelWebhook]), [
                'content' => 'Hello from LANager!',
            ]);

        $testResponse->assertRedirect(route('lans.discord-channel-webhooks.index', $this->lan));
        $testResponse->assertSessionHas('success', trans('phrase.discord-message-sent-successfully', ['purpose' => trans('title.'.$this->discordChannelWebhook->purpose)]));
        Http::assertSent(fn ($req) => $req->url() === self::WEBHOOK_URL);
    }

    public function test_store_redirects_with_error_flash_when_discord_api_fails(): void
    {
        Http::fake([self::WEBHOOK_URL => Http::response(['message' => 'Unknown Webhook'], 404)]);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.messages.store', [$this->lan, $this->discordChannelWebhook]), [
                'content' => 'Hello from LANager!',
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
    }

    public function test_store_returns_redirect_with_error_when_content_is_missing(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.messages.store', [$this->lan, $this->discordChannelWebhook]), []);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        Http::assertNothingSent();
    }

    public function test_store_returns_redirect_with_error_when_content_exceeds_2000_chars(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.messages.store', [$this->lan, $this->discordChannelWebhook]), [
                'content' => str_repeat('a', 2001),
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        Http::assertNothingSent();
    }

    public function test_store_returns_404_when_webhook_belongs_to_different_lan(): void
    {
        Http::fake();
        $otherLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.messages.store', [$otherLan, $this->discordChannelWebhook]), [
                'content' => 'Hello!',
            ]);

        $testResponse->assertStatus(404);
        Http::assertNothingSent();
    }

    public function test_store_returns_403_for_non_admin(): void
    {
        Http::fake();

        $testResponse = $this->actingAs($this->regularUser)
            ->post(route('lans.discord-channel-webhooks.messages.store', [$this->lan, $this->discordChannelWebhook]), [
                'content' => 'Hello!',
            ]);

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }
}
