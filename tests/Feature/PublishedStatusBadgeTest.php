<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Models\User;

class PublishedStatusBadgeTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $adminUser;

    private const PUBLISHED_BADGE = 'badge text-bg-success';

    private const DRAFT_BADGE = 'badge text-bg-secondary';

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);
    }

    public function test_admin_sees_published_and_draft_badges_on_lans_index(): void
    {
        Lan::create([
            'name' => 'Published LAN',
            'start' => '2026-06-01 18:00',
            'end' => '2026-06-03 18:00',
            'published' => true,
        ]);
        Lan::create([
            'name' => 'Draft LAN',
            'start' => '2026-07-01 18:00',
            'end' => '2026-07-03 18:00',
            'published' => false,
        ]);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.index'));

        $testResponse->assertSee(self::PUBLISHED_BADGE, false);
        $testResponse->assertSee(self::DRAFT_BADGE, false);
        $testResponse->assertDontSee('Unpublished');
    }

    public function test_non_admin_does_not_see_published_column_on_lans_index(): void
    {
        Lan::create([
            'name' => 'Published LAN',
            'start' => '2026-06-01 18:00',
            'end' => '2026-06-03 18:00',
            'published' => true,
        ]);

        $testResponse = $this->get(route('lans.index'));

        $testResponse->assertSee('Published LAN');
        $testResponse->assertDontSee(self::PUBLISHED_BADGE, false);
        $testResponse->assertDontSee(self::DRAFT_BADGE, false);
    }

    public function test_admin_sees_published_and_draft_badges_on_guides_index(): void
    {
        $lan = Lan::create([
            'name' => 'My Great LAN',
            'start' => '2026-06-01 18:00',
            'end' => '2026-06-03 18:00',
            'published' => true,
        ]);
        Guide::create([
            'lan_id' => $lan->id,
            'title' => 'Published Guide',
            'content' => 'Content',
            'published' => true,
        ]);
        Guide::create([
            'lan_id' => $lan->id,
            'title' => 'Draft Guide',
            'content' => 'Content',
            'published' => false,
        ]);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.guides.index', ['lan' => $lan]));

        $testResponse->assertSee(self::PUBLISHED_BADGE, false);
        $testResponse->assertSee(self::DRAFT_BADGE, false);
        $testResponse->assertDontSee('Unpublished');
    }

    public function test_non_admin_does_not_see_published_column_on_guides_index(): void
    {
        $lan = Lan::create([
            'name' => 'My Great LAN',
            'start' => '2026-06-01 18:00',
            'end' => '2026-06-03 18:00',
            'published' => true,
        ]);
        Guide::create([
            'lan_id' => $lan->id,
            'title' => 'Published Guide',
            'content' => 'Content',
            'published' => true,
        ]);

        $testResponse = $this->get(route('lans.guides.index', ['lan' => $lan]));

        $testResponse->assertSee('Published Guide');
        $testResponse->assertDontSee(self::PUBLISHED_BADGE, false);
        $testResponse->assertDontSee(self::DRAFT_BADGE, false);
    }

    public function test_admin_sees_published_and_draft_badges_on_events_index(): void
    {
        $lan = Lan::create([
            'name' => 'My Great LAN',
            'start' => '2026-06-01 18:00',
            'end' => '2026-06-03 18:00',
            'published' => true,
        ]);
        Event::create([
            'lan_id' => $lan->id,
            'name' => 'Published Event',
            'start' => '2026-06-01 19:00',
            'end' => '2026-06-01 20:00',
            'published' => true,
        ]);
        Event::create([
            'lan_id' => $lan->id,
            'name' => 'Draft Event',
            'start' => '2026-06-02 19:00',
            'end' => '2026-06-02 20:00',
            'published' => false,
        ]);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.events.index', ['lan' => $lan]));

        $testResponse->assertSee(self::PUBLISHED_BADGE, false);
        $testResponse->assertSee(self::DRAFT_BADGE, false);
        $testResponse->assertDontSee('Unpublished');
    }

    public function test_non_admin_does_not_see_published_column_on_events_index(): void
    {
        $lan = Lan::create([
            'name' => 'My Great LAN',
            'start' => '2026-06-01 18:00',
            'end' => '2026-06-03 18:00',
            'published' => true,
        ]);
        Event::create([
            'lan_id' => $lan->id,
            'name' => 'Published Event',
            'start' => '2026-06-01 19:00',
            'end' => '2026-06-01 20:00',
            'published' => true,
        ]);

        $testResponse = $this->get(route('lans.events.index', ['lan' => $lan]));

        $testResponse->assertSee('Published Event');
        $testResponse->assertDontSee(self::PUBLISHED_BADGE, false);
        $testResponse->assertDontSee(self::DRAFT_BADGE, false);
    }

    public function test_admin_sees_published_and_draft_badges_and_header_on_slides_index(): void
    {
        $lan = Lan::create([
            'name' => 'My Great LAN',
            'start' => '2026-06-01 18:00',
            'end' => '2026-06-03 18:00',
            'published' => true,
        ]);
        Slide::create([
            'lan_id' => $lan->id,
            'name' => 'Published Slide',
            'content' => 'Content',
            'position' => 1,
            'duration' => 1,
            'published' => true,
        ]);
        Slide::create([
            'lan_id' => $lan->id,
            'name' => 'Draft Slide',
            'content' => 'Content',
            'position' => 2,
            'duration' => 1,
            'published' => false,
        ]);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.slides.index', ['lan' => $lan]));

        $testResponse->assertSee(self::PUBLISHED_BADGE, false);
        $testResponse->assertSee(self::DRAFT_BADGE, false);
        $testResponse->assertSee('Published');
        $testResponse->assertDontSee('Unpublished');
    }

    // Note: the slides index route itself requires the admin role
    // (SlidePolicy::index), so there is no non-admin case to test here -
    // a non-admin cannot reach this page at all.
}
