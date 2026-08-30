<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class ApiTokenAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private const string TEST_WEBHOOK_URL = 'https://discord.com/api/webhooks/123456789012345678/abcdefghijklmnopqrstuvwxyz_ABCDEF-test';

    private Lan $lan;

    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->lan = Lan::factory()->create();

        $this->event = Event::factory()->create([
            'lan_id' => $this->lan->id,
            'start' => $this->lan->start,
            'end' => $this->lan->end,
        ]);

        DiscordChannelWebhook::factory()->test()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::TEST_WEBHOOK_URL,
        ]);
    }

    public function test_valid_token_from_authorized_user_authenticates_the_request(): void
    {
        Http::fake([self::TEST_WEBHOOK_URL => Http::response(null, 204)]);

        $adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $adminUser->roles()->attach($adminRole->id, ['assigned_by' => $adminUser->id]);
        $token = $adminUser->createToken('test')->plainTextToken;

        $testResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Hello from a token-authenticated client!',
            ]);

        $testResponse->assertOk();
        Http::assertSent(fn ($req) => $req->url() === self::TEST_WEBHOOK_URL);
    }

    public function test_valid_token_from_unauthorized_user_is_denied(): void
    {
        Http::fake();

        // Ensure this test's user isn't the first user ever created, which is auto-promoted to super-admin
        User::factory()->create();

        $regularUser = User::factory()->create();
        $token = $regularUser->createToken('test')->plainTextToken;

        $testResponse = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Hello!',
            ]);

        $testResponse->assertStatus(403);
        Http::assertNothingSent();
    }

    public function test_revoked_token_is_rejected(): void
    {
        Http::fake();

        $adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $adminUser->roles()->attach($adminRole->id, ['assigned_by' => $adminUser->id]);
        $newAccessToken = $adminUser->createToken('test');
        $plainTextToken = $newAccessToken->plainTextToken;
        $newAccessToken->accessToken->delete();

        $testResponse = $this->withHeader('Authorization', 'Bearer '.$plainTextToken)
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Hello!',
            ]);

        $testResponse->assertStatus(401);
        Http::assertNothingSent();
    }

    public function test_unrecognised_token_is_rejected(): void
    {
        Http::fake();

        $testResponse = $this->withHeader('Authorization', 'Bearer 999|not-a-real-token')
            ->postJson(route('api.v1.events.discord-notification-message.previews.store', ['event' => $this->event]), [
                'message' => 'Hello!',
            ]);

        $testResponse->assertStatus(401);
        Http::assertNothingSent();
    }
}
