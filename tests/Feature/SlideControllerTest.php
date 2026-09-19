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

    // --- LAN time range ---

    private function fixedLan(): Lan
    {
        return Lan::factory()->create([
            'start' => '2026-06-05 12:00:00',
            'end' => '2026-06-07 12:00:00',
        ]);
    }

    private function slideInput(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ranged Slide',
            'content' => 'Some content',
            'position' => '1',
            'duration' => '10',
            'start' => '2026-06-05 18:00',
            'end' => '2026-06-05 19:00',
        ], $overrides);
    }

    public function test_store_creates_a_slide_within_the_lan_time_range(): void
    {
        $lan = $this->fixedLan();

        $this->actingAs($this->adminUser)
            ->post(route('lans.slides.store', ['lan' => $lan]), $this->slideInput())
            ->assertSessionMissing('error');

        $this->assertDatabaseHas('slides', ['name' => 'Ranged Slide', 'lan_id' => $lan->id]);
    }

    public function test_store_accepts_times_exactly_matching_the_lan_bounds(): void
    {
        $lan = $this->fixedLan();

        $this->actingAs($this->adminUser)
            ->post(route('lans.slides.store', ['lan' => $lan]), $this->slideInput([
                'start' => '2026-06-05 12:00',
                'end' => '2026-06-07 12:00',
            ]))
            ->assertSessionMissing('error');

        $this->assertDatabaseHas('slides', ['name' => 'Ranged Slide']);
    }

    public function test_store_accepts_a_slide_with_no_start_or_end(): void
    {
        $lan = $this->fixedLan();

        $this->actingAs($this->adminUser)
            ->post(route('lans.slides.store', ['lan' => $lan]), $this->slideInput(['start' => null, 'end' => null]))
            ->assertSessionMissing('error');

        $this->assertDatabaseHas('slides', ['name' => 'Ranged Slide', 'start' => null, 'end' => null]);
    }

    public function test_store_accepts_a_slide_with_only_an_in_range_start(): void
    {
        $lan = $this->fixedLan();

        $this->actingAs($this->adminUser)
            ->post(route('lans.slides.store', ['lan' => $lan]), $this->slideInput(['end' => null]))
            ->assertSessionMissing('error');

        $this->assertDatabaseHas('slides', ['name' => 'Ranged Slide']);
    }

    public function test_store_rejects_a_start_before_the_lan_starts(): void
    {
        $lan = $this->fixedLan();

        $this->actingAs($this->adminUser)
            ->post(route('lans.slides.store', ['lan' => $lan]), $this->slideInput(['start' => '2026-06-05 11:59']))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('slides', ['name' => 'Ranged Slide']);
    }

    public function test_store_rejects_an_end_after_the_lan_ends(): void
    {
        $lan = $this->fixedLan();

        $this->actingAs($this->adminUser)
            ->post(route('lans.slides.store', ['lan' => $lan]), $this->slideInput(['end' => '2026-06-07 12:01']))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('slides', ['name' => 'Ranged Slide']);
    }

    public function test_store_rejects_an_out_of_range_end_when_there_is_no_start(): void
    {
        $lan = $this->fixedLan();

        $this->actingAs($this->adminUser)
            ->post(route('lans.slides.store', ['lan' => $lan]), $this->slideInput([
                'start' => null,
                'end' => '2026-06-08 12:00',
            ]))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('slides', ['name' => 'Ranged Slide']);
    }

    public function test_update_rejects_moving_a_slide_out_of_range_and_keeps_its_values(): void
    {
        $lan = $this->fixedLan();
        $slide = $this->createSlide([
            'lan_id' => $lan->id,
            'start' => '2026-06-05 18:00:00',
            'end' => '2026-06-05 19:00:00',
        ]);

        $this->actingAs($this->adminUser)
            ->put(route('lans.slides.update', ['lan' => $lan, 'slide' => $slide]), $this->slideInput([
                'name' => 'Changed Name',
                'end' => '2026-06-09 19:00',
            ]))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('slides', ['id' => $slide->id, 'name' => 'My Slide', 'end' => '2026-06-05 19:00:00']);
    }

    public function test_update_accepts_a_change_within_the_lan_time_range(): void
    {
        $lan = $this->fixedLan();
        $slide = $this->createSlide(['lan_id' => $lan->id]);

        $this->actingAs($this->adminUser)
            ->put(route('lans.slides.update', ['lan' => $lan, 'slide' => $slide]), $this->slideInput(['name' => 'Changed Name']))
            ->assertSessionMissing('error');

        $this->assertDatabaseHas('slides', ['id' => $slide->id, 'name' => 'Changed Name']);
    }

    public function test_publish_does_not_publish_a_slide_outside_the_lan_time_range_and_flashes_an_error(): void
    {
        $lan = $this->fixedLan();
        $slide = $this->createSlide([
            'lan_id' => $lan->id,
            'published' => false,
            'start' => '2026-06-01 10:00:00',
            'end' => '2026-06-01 12:00:00',
        ]);

        $this->actingAs($this->adminUser)
            ->patch(route('lans.slides.publish', ['lan' => $lan, 'slide' => $slide]))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('slides', ['id' => $slide->id, 'published' => false]);
    }
}
