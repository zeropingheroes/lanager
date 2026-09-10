<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Models\User;

class SlideControllerTest extends TestCase
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

    private function createSlide(array $overrides = []): Slide
    {
        return Slide::create(array_merge([
            'lan_id' => $this->lan->id,
            'name' => 'My Slide',
            'content' => 'Some content',
            'position' => 1,
            'duration' => 10,
            'published' => false,
        ], $overrides));
    }

    // --- publish()/unpublish() ---

    public function test_publish_sets_published_true_for_a_valid_draft_slide(): void
    {
        $slide = $this->createSlide(['published' => false]);

        $testResponse = $this->actingAs($this->adminUser)
            ->patch(route('lans.slides.publish', ['lan' => $this->lan, 'slide' => $slide]));

        $testResponse->assertRedirect();
        $this->assertDatabaseHas('slides', ['id' => $slide->id, 'published' => true]);
    }

    public function test_unpublish_sets_published_false_for_a_published_slide(): void
    {
        $slide = $this->createSlide(['published' => true]);

        $testResponse = $this->actingAs($this->adminUser)
            ->patch(route('lans.slides.unpublish', ['lan' => $this->lan, 'slide' => $slide]));

        $testResponse->assertRedirect();
        $this->assertDatabaseHas('slides', ['id' => $slide->id, 'published' => false]);
    }

    public function test_publish_does_not_publish_a_slide_with_end_before_start_and_flashes_an_error(): void
    {
        $slide = $this->createSlide([
            'published' => false,
            'start' => now()->addHour(),
            'end' => now(),
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->patch(route('lans.slides.publish', ['lan' => $this->lan, 'slide' => $slide]));

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertDatabaseHas('slides', ['id' => $slide->id, 'published' => false]);
    }

    public function test_publish_returns_403_for_non_admin_and_does_not_publish(): void
    {
        $slide = $this->createSlide(['published' => false]);

        $testResponse = $this->actingAs($this->regularUser)
            ->patch(route('lans.slides.publish', ['lan' => $this->lan, 'slide' => $slide]));

        $testResponse->assertStatus(403);
        $this->assertDatabaseHas('slides', ['id' => $slide->id, 'published' => false]);
    }

    public function test_unpublish_returns_403_for_non_admin_and_does_not_unpublish(): void
    {
        $slide = $this->createSlide(['published' => true]);

        $testResponse = $this->actingAs($this->regularUser)
            ->patch(route('lans.slides.unpublish', ['lan' => $this->lan, 'slide' => $slide]));

        $testResponse->assertStatus(403);
        $this->assertDatabaseHas('slides', ['id' => $slide->id, 'published' => true]);
    }
}
