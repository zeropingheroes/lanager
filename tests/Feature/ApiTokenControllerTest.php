<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\User;

class ApiTokenControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $user;

    private User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->user = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    public function test_index_only_shows_the_current_users_own_tokens(): void
    {
        $this->user->createToken('mine');
        $this->otherUser->createToken('not-mine');

        $testResponse = $this->actingAs($this->user)
            ->get(route('api-tokens.index'));

        $testResponse->assertStatus(200);
        $testResponse->assertViewIs('pages.api-tokens.index');
        $testResponse->assertViewHas('tokens', function ($tokens): bool {
            return $tokens->count() === 1 && $tokens->first()->name === 'mine';
        });
    }

    public function test_home_page_links_to_api_tokens_for_logged_in_user(): void
    {
        $testResponse = $this->actingAs($this->user)
            ->followingRedirects()
            ->get(route('home'));

        $testResponse->assertOk();
        $testResponse->assertSee(route('api-tokens.index'), false);
    }

    public function test_index_redirects_guest_to_login(): void
    {
        $testResponse = $this->get(route('api-tokens.index'));

        $testResponse->assertRedirect(route('login'));
    }

    public function test_store_creates_a_token_for_the_current_user(): void
    {
        $testResponse = $this->actingAs($this->user)
            ->post(route('api-tokens.store'), ['name' => 'My Script']);

        $testResponse->assertRedirect(route('api-tokens.index'));
        $this->assertSame(1, $this->user->tokens()->count());
        $this->assertSame('My Script', $this->user->tokens()->first()->name);
    }

    public function test_store_returns_error_when_name_is_missing(): void
    {
        $testResponse = $this->actingAs($this->user)
            ->post(route('api-tokens.store'), []);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertSame(0, $this->user->tokens()->count());
    }

    public function test_destroy_revokes_the_current_users_own_token(): void
    {
        $token = $this->user->createToken('mine');

        $testResponse = $this->actingAs($this->user)
            ->delete(route('api-tokens.destroy', ['api_token' => $token->accessToken->id]));

        $testResponse->assertRedirect(route('api-tokens.index'));
        $this->assertSame(0, $this->user->tokens()->count());
    }

    public function test_destroy_returns_404_and_leaves_token_valid_for_another_users_token(): void
    {
        $token = $this->otherUser->createToken('not-mine');

        $testResponse = $this->actingAs($this->user)
            ->delete(route('api-tokens.destroy', ['api_token' => $token->accessToken->id]));

        $testResponse->assertStatus(404);
        $this->assertSame(1, $this->otherUser->tokens()->count());
    }
}
