<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class GuideControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

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

    // --- publish()/unpublish() ---

    public function test_publish_sets_published_true_for_a_valid_draft_guide(): void
    {
        $guide = Guide::create([
            'lan_id' => $this->lan->id,
            'title' => 'My Guide',
            'content' => 'Some content',
            'published' => false,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->patch(route('lans.guides.publish', ['lan' => $this->lan, 'guide' => $guide]));

        $testResponse->assertRedirect();
        $this->assertDatabaseHas('guides', ['id' => $guide->id, 'published' => true]);
    }

    public function test_unpublish_sets_published_false_for_a_published_guide(): void
    {
        $guide = Guide::create([
            'lan_id' => $this->lan->id,
            'title' => 'My Guide',
            'content' => 'Some content',
            'published' => true,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->patch(route('lans.guides.unpublish', ['lan' => $this->lan, 'guide' => $guide]));

        $testResponse->assertRedirect();
        $this->assertDatabaseHas('guides', ['id' => $guide->id, 'published' => false]);
    }

    public function test_publish_does_not_publish_a_guide_without_content_and_flashes_an_error(): void
    {
        $guide = Guide::create([
            'lan_id' => $this->lan->id,
            'title' => 'My Guide',
            'content' => null,
            'published' => false,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->patch(route('lans.guides.publish', ['lan' => $this->lan, 'guide' => $guide]));

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertDatabaseHas('guides', ['id' => $guide->id, 'published' => false]);
    }

    public function test_publish_returns_403_for_non_admin_and_does_not_publish(): void
    {
        $guide = Guide::create([
            'lan_id' => $this->lan->id,
            'title' => 'My Guide',
            'content' => 'Some content',
            'published' => false,
        ]);

        $testResponse = $this->actingAs($this->regularUser)
            ->patch(route('lans.guides.publish', ['lan' => $this->lan, 'guide' => $guide]));

        $testResponse->assertStatus(403);
        $this->assertDatabaseHas('guides', ['id' => $guide->id, 'published' => false]);
    }

    public function test_unpublish_returns_403_for_non_admin_and_does_not_unpublish(): void
    {
        $guide = Guide::create([
            'lan_id' => $this->lan->id,
            'title' => 'My Guide',
            'content' => 'Some content',
            'published' => true,
        ]);

        $testResponse = $this->actingAs($this->regularUser)
            ->patch(route('lans.guides.unpublish', ['lan' => $this->lan, 'guide' => $guide]));

        $testResponse->assertStatus(403);
        $this->assertDatabaseHas('guides', ['id' => $guide->id, 'published' => true]);
    }
}
