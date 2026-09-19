<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Models\User;

class SlideCloneControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $adminUser;

    private User $nonAdminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);

        $this->nonAdminUser = User::factory()->create();
    }

    private function sourceLan(array $overrides = []): Lan
    {
        return Lan::factory()->create(array_merge([
            'start' => Carbon::parse('2026-06-05 12:00'),
            'end' => Carbon::parse('2026-06-07 12:00'),
            'published' => true,
        ], $overrides));
    }

    private function sourceSlide(Lan $lan, array $overrides = []): Slide
    {
        return Slide::create(array_merge([
            'lan_id' => $lan->id,
            'name' => 'Source Slide',
            'content' => 'Source content',
            'position' => 3,
            'duration' => 15,
            'start' => Carbon::parse('2026-06-05 19:00'),
            'end' => Carbon::parse('2026-06-05 20:00'),
            'published' => true,
        ], $overrides));
    }

    private function validCloneInput(Lan $destinationLan, array $overrides = []): array
    {
        return array_merge([
            'lan_id' => $destinationLan->id,
            'name' => 'Cloned Slide',
            'content' => 'Cloned content',
            'position' => '4',
            'duration' => '20',
            'start' => $destinationLan->start->copy()->addHours(6)->format('Y-m-d H:i'),
            'end' => $destinationLan->start->copy()->addHours(8)->format('Y-m-d H:i'),
        ], $overrides);
    }

    private function cloneStoreRoute(Slide $slide): string
    {
        return route('lans.slides.clone.store', ['lan' => $slide->lan_id, 'slide' => $slide]);
    }

    private function cloneCreateRoute(Slide $slide): string
    {
        return route('lans.slides.clone.create', ['lan' => $slide->lan_id, 'slide' => $slide]);
    }

    // --- access ---

    public function test_non_admin_cannot_view_clone_form(): void
    {
        $slide = $this->sourceSlide($this->sourceLan());

        $this->actingAs($this->nonAdminUser)->get($this->cloneCreateRoute($slide))->assertForbidden();
    }

    public function test_non_admin_cannot_submit_clone_form(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan);

        $testResponse = $this->actingAs($this->nonAdminUser)
            ->post($this->cloneStoreRoute($slide), $this->validCloneInput($lan));

        $testResponse->assertForbidden();
        $this->assertDatabaseMissing('slides', ['name' => 'Cloned Slide']);
    }

    public function test_clone_action_is_shown_to_admins_and_the_list_is_closed_to_non_admins(): void
    {
        $slide = $this->sourceSlide($this->sourceLan());
        $indexRoute = route('lans.slides.index', ['lan' => $slide->lan_id]);

        $this->actingAs($this->adminUser)->get($indexRoute)->assertOk()->assertSee($this->cloneCreateRoute($slide), false);
        $this->actingAs($this->nonAdminUser)->get($indexRoute)->assertForbidden();
    }

    public function test_clone_form_returns_not_found_when_slide_is_not_on_the_lan_in_the_url(): void
    {
        $slide = $this->sourceSlide($this->sourceLan());
        $otherLan = $this->sourceLan();

        $this->actingAs($this->adminUser)
            ->get(route('lans.slides.clone.create', ['lan' => $otherLan, 'slide' => $slide]))
            ->assertNotFound();
    }

    public function test_clone_submission_returns_not_found_when_slide_is_not_on_the_lan_in_the_url(): void
    {
        $slide = $this->sourceSlide($this->sourceLan());
        $otherLan = $this->sourceLan();

        $testResponse = $this->actingAs($this->adminUser)->post(
            route('lans.slides.clone.store', ['lan' => $otherLan, 'slide' => $slide]),
            $this->validCloneInput($otherLan)
        );

        $testResponse->assertNotFound();
        $this->assertDatabaseMissing('slides', ['name' => 'Cloned Slide']);
    }

    // --- form ---

    public function test_admin_can_view_the_clone_form_prefilled_with_the_sources_values(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan);

        $testResponse = $this->actingAs($this->adminUser)->get($this->cloneCreateRoute($slide));

        $testResponse->assertOk();
        $testResponse->assertSee('value="Source Slide"', false);
        $testResponse->assertSee('Source content', false);
        $testResponse->assertSee('value="3"', false);
        $testResponse->assertSee('value="15"', false);
        $testResponse->assertSee('value="2026-06-05 19:00:00"', false);
        $testResponse->assertSee('value="2026-06-05 20:00:00"', false);
        $testResponse->assertSee('id="lans-data"', false);
        $testResponse->assertSee('id="clone-form-out-of-range-warning"', false);
        $this->assertMatchesRegularExpression(
            '/<option\s+selected\s+value="'.$lan->id.'"/',
            $testResponse->getContent()
        );
    }

    public function test_clone_form_has_empty_start_and_end_when_the_source_has_none(): void
    {
        $slide = $this->sourceSlide($this->sourceLan(), ['start' => null, 'end' => null]);

        $testResponse = $this->actingAs($this->adminUser)->get($this->cloneCreateRoute($slide));

        $testResponse->assertOk();
        $testResponse->assertSee('id="start"', false);
        $testResponse->assertSee('name="start"', false);
        $this->assertMatchesRegularExpression('/name="start"[^>]*value=""/s', $testResponse->getContent());
        $this->assertMatchesRegularExpression('/name="end"[^>]*value=""/s', $testResponse->getContent());
    }

    // --- valid submissions ---

    public function test_admin_can_clone_a_slide_to_its_own_lan(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan);

        $testResponse = $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($slide), $this->validCloneInput($lan, ['name' => 'Same Lan Clone']));

        $newSlide = Slide::where('name', 'Same Lan Clone')->firstOrFail();
        $testResponse->assertRedirect(route('lans.slides.index', ['lan' => $lan]));
        $testResponse->assertSessionHas('success');
        $this->assertSame($lan->id, $newSlide->lan_id);
        $this->assertSame('Cloned content', $newSlide->content);
        $this->assertSame(4, $newSlide->position);
        $this->assertSame(20, $newSlide->duration);
        $this->assertSame('2026-06-05 18:00', $newSlide->start->format('Y-m-d H:i'));
        $this->assertSame('2026-06-05 20:00', $newSlide->end->format('Y-m-d H:i'));
    }

    public function test_admin_can_clone_a_slide_to_a_different_lan(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan);
        $destinationLan = $this->sourceLan(['start' => '2026-07-01 12:00', 'end' => '2026-07-03 12:00']);

        $testResponse = $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($slide), $this->validCloneInput($destinationLan, ['name' => 'Other Lan Clone']));

        $newSlide = Slide::where('name', 'Other Lan Clone')->firstOrFail();
        $this->assertSame($destinationLan->id, $newSlide->lan_id);
        $testResponse->assertRedirect(route('lans.slides.index', ['lan' => $destinationLan]));
    }

    public function test_admin_can_clone_a_slide_without_start_and_end(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan, ['start' => null, 'end' => null]);

        $this->actingAs($this->adminUser)->post(
            $this->cloneStoreRoute($slide),
            $this->validCloneInput($lan, ['name' => 'No Dates Clone', 'start' => '', 'end' => ''])
        );

        $newSlide = Slide::where('name', 'No Dates Clone')->firstOrFail();
        $this->assertNull($newSlide->start);
        $this->assertNull($newSlide->end);
    }

    public function test_a_slide_outside_the_destination_lan_time_range_is_rejected(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan);

        $testResponse = $this->actingAs($this->adminUser)->post(
            $this->cloneStoreRoute($slide),
            $this->validCloneInput($lan, [
                'name' => 'Out Of Range Clone',
                'start' => '2026-06-01 10:00',
                'end' => '2026-06-10 10:00',
            ])
        );

        $testResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('slides', ['name' => 'Out Of Range Clone']);
    }

    public function test_a_slide_is_checked_against_the_destination_lan_not_the_source_lan(): void
    {
        $lan = $this->sourceLan();
        $destinationLan = $this->sourceLan([
            'start' => Carbon::parse('2026-09-05 12:00'),
            'end' => Carbon::parse('2026-09-07 12:00'),
        ]);
        $slide = $this->sourceSlide($lan);

        // Inside the source LAN but outside the destination LAN
        $this->actingAs($this->adminUser)->post(
            $this->cloneStoreRoute($slide),
            $this->validCloneInput($destinationLan, [
                'name' => 'Source Range Clone',
                'start' => '2026-06-05 19:00',
                'end' => '2026-06-05 20:00',
            ])
        )->assertSessionHas('error');
        $this->assertDatabaseMissing('slides', ['name' => 'Source Range Clone']);

        // Inside the destination LAN
        $this->actingAs($this->adminUser)->post(
            $this->cloneStoreRoute($slide),
            $this->validCloneInput($destinationLan, ['name' => 'Destination Range Clone'])
        )->assertSessionMissing('error');
        $this->assertDatabaseHas('slides', ['name' => 'Destination Range Clone', 'lan_id' => $destinationLan->id]);
    }

    public function test_source_slide_is_unchanged_after_cloning(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan);
        $destinationLan = $this->sourceLan(['start' => '2026-07-01 12:00', 'end' => '2026-07-03 12:00']);

        $this->actingAs($this->adminUser)->post(
            $this->cloneStoreRoute($slide),
            $this->validCloneInput($destinationLan, ['name' => 'Different Name', 'content' => 'Different content'])
        );

        $slide->refresh();
        $this->assertSame('Source Slide', $slide->name);
        $this->assertSame('Source content', $slide->content);
        $this->assertSame($lan->id, $slide->lan_id);
        $this->assertSame(3, $slide->position);
        $this->assertSame(15, $slide->duration);
        $this->assertSame('2026-06-05 19:00', $slide->start->format('Y-m-d H:i'));
        $this->assertSame('2026-06-05 20:00', $slide->end->format('Y-m-d H:i'));
        $this->assertTrue((bool) $slide->published);
    }

    // --- published ---

    public function test_published_box_checked_creates_a_published_slide(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan, ['published' => false]);

        $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($slide), $this->validCloneInput($lan, ['name' => 'Cloned Published', 'published' => '1']));

        $this->assertTrue((bool) Slide::where('name', 'Cloned Published')->firstOrFail()->published);
    }

    public function test_published_box_unchecked_creates_an_unpublished_slide_even_if_source_is_published(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan, ['published' => true]);

        $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($slide), $this->validCloneInput($lan, ['name' => 'Cloned Unpublished']));

        $this->assertFalse((bool) Slide::where('name', 'Cloned Unpublished')->firstOrFail()->published);
    }

    // --- validation ---

    public static function invalidInputProvider(): array
    {
        return [
            'missing name' => [['name' => '']],
            'missing content' => [['content' => '']],
            'missing position' => [['position' => '']],
            'missing duration' => [['duration' => '']],
            'start not before end' => [['start' => '2026-06-06 12:00', 'end' => '2026-06-06 11:00']],
            'unknown lan' => [['lan_id' => 999999]],
        ];
    }

    #[DataProvider('invalidInputProvider')]
    public function test_invalid_input_is_rejected_and_no_slide_is_created(array $overrides): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan);
        $slideCount = Slide::count();

        $testResponse = $this->actingAs($this->adminUser)
            ->from($this->cloneCreateRoute($slide))
            ->post($this->cloneStoreRoute($slide), $this->validCloneInput($lan, $overrides));

        $testResponse->assertRedirect($this->cloneCreateRoute($slide));
        $testResponse->assertSessionHas('error');
        $this->assertSame($slideCount, Slide::count());
    }

    public function test_rejected_submission_keeps_the_submitted_input(): void
    {
        $lan = $this->sourceLan();
        $slide = $this->sourceSlide($lan);

        $testResponse = $this->actingAs($this->adminUser)
            ->from($this->cloneCreateRoute($slide))
            ->post($this->cloneStoreRoute($slide), $this->validCloneInput($lan, ['name' => '', 'content' => 'Kept content']));

        $testResponse->assertSessionHasInput('content', 'Kept content');
    }
}
