<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class DiscordChannelWebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private const string VALID_WEBHOOK_URL = 'https://discord.com/api/webhooks/123456789012345678/abcdefghijklmnopqrstuvwxyz_ABCDEF-token';

    private const string VALID_WEBHOOK_URL_2 = 'https://discord.com/api/webhooks/987654321098765432/zyxwvutsrqponmlkjihgfedcba_ZYXWVU-alt';

    private User $adminUser;

    private User $regularUser;

    private Lan $lan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->lan = Lan::factory()->create();

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);

        $this->regularUser = User::factory()->create();
    }

    public function test_index_returns_view_with_webhooks_for_admin(): void
    {
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::VALID_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.discord-channel-webhooks.index', $this->lan));

        $testResponse->assertStatus(200);
        $testResponse->assertViewIs('pages.discord-channel-webhooks.index');
        $testResponse->assertViewHas('webhooks');
    }

    public function test_index_returns_403_for_non_admin(): void
    {
        $testResponse = $this->actingAs($this->regularUser)
            ->get(route('lans.discord-channel-webhooks.index', $this->lan));

        $testResponse->assertStatus(403);
    }

    public function test_index_shows_action_buttons_for_each_webhook(): void
    {
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::VALID_WEBHOOK_URL]);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.discord-channel-webhooks.index', $this->lan));

        $testResponse->assertStatus(200);
        $testResponse->assertSee(trans('title.test-post'));
        $testResponse->assertSee(trans('title.compose'));
        $testResponse->assertSee(trans('title.delete'));
    }

    public function test_index_disables_creation_form_when_both_purposes_are_configured(): void
    {
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::VALID_WEBHOOK_URL]);
        DiscordChannelWebhook::factory()->test()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::VALID_WEBHOOK_URL_2]);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.discord-channel-webhooks.index', $this->lan));

        $testResponse->assertStatus(200);
        $testResponse->assertViewHas('availablePurposes', []);
        $testResponse->assertSee('disabled', false);
    }

    public function test_store_creates_webhook_for_admin(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.store', $this->lan), [
                'purpose' => 'live',
                'webhook_url' => self::VALID_WEBHOOK_URL,
            ]);

        $testResponse->assertRedirect(route('lans.discord-channel-webhooks.index', $this->lan));

        $this->assertDatabaseHas('discord_channel_webhooks', [
            'lan_id' => $this->lan->id,
            'purpose' => 'live',
            'webhook_url' => self::VALID_WEBHOOK_URL,
        ]);
    }

    public function test_store_rejects_duplicate_purpose_for_same_lan(): void
    {
        DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::VALID_WEBHOOK_URL,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.store', $this->lan), [
                'purpose' => 'live',
                'webhook_url' => self::VALID_WEBHOOK_URL_2,
            ]);

        $testResponse->assertRedirect();
        $this->assertDatabaseCount('discord_channel_webhooks', 1);
    }

    public function test_store_rejects_duplicate_webhook_url_for_same_lan(): void
    {
        DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::VALID_WEBHOOK_URL,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.store', $this->lan), [
                'purpose' => 'test',
                'webhook_url' => self::VALID_WEBHOOK_URL,
            ]);

        $testResponse->assertRedirect();
        $this->assertDatabaseCount('discord_channel_webhooks', 1);
    }

    public function test_store_rejects_invalid_discord_url(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.store', $this->lan), [
                'purpose' => 'live',
                'webhook_url' => 'https://example.com/not-a-discord-webhook',
            ]);

        $testResponse->assertRedirect();
        $this->assertDatabaseMissing('discord_channel_webhooks', ['lan_id' => $this->lan->id]);
    }

    public function test_store_rejects_missing_purpose(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.store', $this->lan), [
                'webhook_url' => self::VALID_WEBHOOK_URL,
            ]);

        $testResponse->assertRedirect();
        $this->assertDatabaseMissing('discord_channel_webhooks', ['lan_id' => $this->lan->id]);
    }

    public function test_store_rejects_missing_webhook_url(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.discord-channel-webhooks.store', $this->lan), [
                'purpose' => 'live',
            ]);

        $testResponse->assertRedirect();
        $this->assertDatabaseMissing('discord_channel_webhooks', ['lan_id' => $this->lan->id]);
    }

    public function test_store_returns_403_for_non_admin(): void
    {
        $testResponse = $this->actingAs($this->regularUser)
            ->post(route('lans.discord-channel-webhooks.store', $this->lan), [
                'purpose' => 'live',
                'webhook_url' => self::VALID_WEBHOOK_URL,
            ]);

        $testResponse->assertStatus(403);
        $this->assertDatabaseMissing('discord_channel_webhooks', ['lan_id' => $this->lan->id]);
    }

    public function test_destroy_deletes_webhook_without_affecting_others(): void
    {
        $webhookToDelete = DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::VALID_WEBHOOK_URL,
        ]);

        $webhookToKeep = DiscordChannelWebhook::factory()->test()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::VALID_WEBHOOK_URL_2,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->delete(route('lans.discord-channel-webhooks.destroy', [$this->lan, $webhookToDelete]));

        $testResponse->assertRedirect(route('lans.discord-channel-webhooks.index', $this->lan));
        $this->assertDatabaseMissing('discord_channel_webhooks', ['id' => $webhookToDelete->id]);
        $this->assertDatabaseHas('discord_channel_webhooks', ['id' => $webhookToKeep->id]);
    }

    public function test_destroy_returns_404_when_webhook_belongs_to_different_lan(): void
    {
        $otherLan = Lan::factory()->create();
        $webhook = DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $otherLan->id,
            'webhook_url' => self::VALID_WEBHOOK_URL,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->delete(route('lans.discord-channel-webhooks.destroy', [$this->lan, $webhook]));

        $testResponse->assertStatus(404);
        $this->assertDatabaseHas('discord_channel_webhooks', ['id' => $webhook->id]);
    }

    public function test_destroy_returns_403_for_non_admin(): void
    {
        $webhook = DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::VALID_WEBHOOK_URL,
        ]);

        $testResponse = $this->actingAs($this->regularUser)
            ->delete(route('lans.discord-channel-webhooks.destroy', [$this->lan, $webhook]));

        $testResponse->assertStatus(403);
        $this->assertDatabaseHas('discord_channel_webhooks', ['id' => $webhook->id]);
    }
}
