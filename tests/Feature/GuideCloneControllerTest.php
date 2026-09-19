<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class GuideCloneControllerTest extends TestCase
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

    private function sourceGuide(?Lan $lan = null, array $overrides = []): Guide
    {
        return Guide::create(array_merge([
            'lan_id' => ($lan ?? Lan::factory()->create(['published' => true]))->id,
            'title' => 'Source Guide',
            'content' => 'Source content',
            'published' => true,
        ], $overrides));
    }

    private function validCloneInput(Lan $destinationLan, array $overrides = []): array
    {
        return array_merge([
            'lan_id' => $destinationLan->id,
            'title' => 'Cloned Guide',
            'content' => 'Cloned content',
        ], $overrides);
    }

    private function cloneStoreRoute(Guide $guide): string
    {
        return route('lans.guides.clone.store', ['lan' => $guide->lan_id, 'guide' => $guide]);
    }

    // --- access ---

    public function test_non_admin_cannot_view_clone_form(): void
    {
        $guide = $this->sourceGuide();

        $testResponse = $this->actingAs($this->nonAdminUser)
            ->get(route('lans.guides.clone.create', ['lan' => $guide->lan_id, 'guide' => $guide]));

        $testResponse->assertForbidden();
    }

    public function test_non_admin_cannot_submit_clone_form(): void
    {
        $guide = $this->sourceGuide();

        $testResponse = $this->actingAs($this->nonAdminUser)
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($guide->lan));

        $testResponse->assertForbidden();
        $this->assertDatabaseMissing('guides', ['title' => 'Cloned Guide']);
    }

    public function test_clone_action_is_shown_to_admins_only(): void
    {
        $guide = $this->sourceGuide();
        $showRoute = route('lans.guides.show', [
            'lan' => $guide->lan_id,
            'guide' => $guide,
            'slug' => Str::slug($guide->title),
        ]);
        $cloneRoute = route('lans.guides.clone.create', ['lan' => $guide->lan_id, 'guide' => $guide]);

        $this->actingAs($this->adminUser)->get($showRoute)->assertSee($cloneRoute, false);
        $this->actingAs($this->nonAdminUser)->get($showRoute)->assertOk()->assertDontSee($cloneRoute, false);
    }

    public function test_clone_form_returns_not_found_when_guide_is_not_on_the_lan_in_the_url(): void
    {
        $guide = $this->sourceGuide();
        $otherLan = Lan::factory()->create();

        $this->actingAs($this->adminUser)
            ->get(route('lans.guides.clone.create', ['lan' => $otherLan, 'guide' => $guide]))
            ->assertNotFound();
    }

    public function test_clone_submission_returns_not_found_when_guide_is_not_on_the_lan_in_the_url(): void
    {
        $guide = $this->sourceGuide();
        $otherLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)->post(
            route('lans.guides.clone.store', ['lan' => $otherLan, 'guide' => $guide]),
            $this->validCloneInput($otherLan)
        );

        $testResponse->assertNotFound();
        $this->assertDatabaseMissing('guides', ['title' => 'Cloned Guide']);
    }

    // --- form ---

    public function test_admin_can_view_the_clone_form_prefilled_with_the_sources_values(): void
    {
        $guide = $this->sourceGuide(null, ['title' => 'Source Guide', 'content' => 'Source content']);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.guides.clone.create', ['lan' => $guide->lan_id, 'guide' => $guide]));

        $testResponse->assertOk();
        $testResponse->assertSee('value="Source Guide"', false);
        $testResponse->assertSee('Source content', false);
        $testResponse->assertSee('name="lan_id"', false);
        $this->assertMatchesRegularExpression(
            '/<option\s+selected\s+value="'.$guide->lan_id.'"/',
            $testResponse->getContent()
        );
    }

    // --- valid submissions ---

    public function test_admin_can_clone_a_guide_to_its_own_lan(): void
    {
        $guide = $this->sourceGuide();

        $testResponse = $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($guide->lan, ['title' => 'Same Lan Clone']));

        $newGuide = Guide::where('title', 'Same Lan Clone')->firstOrFail();
        $testResponse->assertRedirect(route('lans.guides.show', ['lan' => $guide->lan_id, 'guide' => $newGuide]));
        $this->assertSame($guide->lan_id, $newGuide->lan_id);
        $this->assertSame('Cloned content', $newGuide->content);
        $testResponse->assertSessionHas('success');
    }

    public function test_admin_can_clone_a_guide_to_a_different_lan(): void
    {
        $guide = $this->sourceGuide();
        $destinationLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($destinationLan, ['title' => 'Other Lan Clone']));

        $newGuide = Guide::where('title', 'Other Lan Clone')->firstOrFail();
        $this->assertSame($destinationLan->id, $newGuide->lan_id);
        $testResponse->assertRedirect(route('lans.guides.show', ['lan' => $destinationLan->id, 'guide' => $newGuide]));
    }

    public function test_source_guide_is_unchanged_after_cloning(): void
    {
        $guide = $this->sourceGuide();
        $destinationLan = Lan::factory()->create();

        $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($destinationLan, ['title' => 'Different Title', 'content' => 'Different content']));

        $guide->refresh();
        $this->assertSame('Source Guide', $guide->title);
        $this->assertSame('Source content', $guide->content);
        $this->assertNotSame($destinationLan->id, $guide->lan_id);
    }

    // --- published ---

    public function test_published_box_checked_creates_a_published_guide(): void
    {
        $guide = $this->sourceGuide(null, ['published' => false]);

        $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($guide->lan, ['title' => 'Cloned Published', 'published' => '1']));

        $this->assertTrue((bool) Guide::where('title', 'Cloned Published')->firstOrFail()->published);
    }

    public function test_published_box_unchecked_creates_an_unpublished_guide_even_if_source_is_published(): void
    {
        $guide = $this->sourceGuide(null, ['published' => true]);

        $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($guide->lan, ['title' => 'Cloned Unpublished']));

        $this->assertFalse((bool) Guide::where('title', 'Cloned Unpublished')->firstOrFail()->published);
    }

    // --- validation ---

    public function test_missing_title_is_rejected_and_no_guide_is_created(): void
    {
        $guide = $this->sourceGuide();

        $testResponse = $this->actingAs($this->adminUser)
            ->from(route('lans.guides.clone.create', ['lan' => $guide->lan_id, 'guide' => $guide]))
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($guide->lan, ['title' => '', 'content' => 'Kept content']));

        $testResponse->assertRedirect(route('lans.guides.clone.create', ['lan' => $guide->lan_id, 'guide' => $guide]));
        $testResponse->assertSessionHas('error');
        $testResponse->assertSessionHasInput('content', 'Kept content');
        $this->assertSame(1, Guide::count());
    }

    public function test_missing_content_is_rejected_and_no_guide_is_created(): void
    {
        $guide = $this->sourceGuide();

        $testResponse = $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($guide->lan, ['content' => '']));

        $testResponse->assertSessionHas('error');
        $this->assertSame(1, Guide::count());
    }

    public function test_unknown_destination_lan_is_rejected_and_no_guide_is_created(): void
    {
        $guide = $this->sourceGuide();

        $testResponse = $this->actingAs($this->adminUser)
            ->post($this->cloneStoreRoute($guide), $this->validCloneInput($guide->lan, ['lan_id' => 999999]));

        $testResponse->assertSessionHas('error');
        $this->assertSame(1, Guide::count());
    }
}
