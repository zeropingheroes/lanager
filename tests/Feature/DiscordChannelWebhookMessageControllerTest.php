<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
