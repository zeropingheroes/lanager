<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class LanIndexCloneActionTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $adminUser;

    private User $nonAdminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);

        $this->nonAdminUser = User::factory()->create();
    }

    public function test_admin_sees_clone_action_on_lans_index(): void
    {
        $lan = Lan::factory()->create([
            'start' => Carbon::parse('2026-06-05 12:00'),
            'end' => Carbon::parse('2026-06-07 12:00'),
            'published' => true,
        ]);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.index'));

        $testResponse->assertSee(route('lans.clone.create', $lan), false);
        $testResponse->assertSee(trans('title.clone'));
    }

    public function test_non_admin_does_not_see_clone_action_on_lans_index(): void
    {
        $lan = Lan::factory()->create([
            'start' => Carbon::parse('2026-06-05 12:00'),
            'end' => Carbon::parse('2026-06-07 12:00'),
            'published' => true,
        ]);

        $testResponse = $this->actingAs($this->nonAdminUser)->get(route('lans.index'));

        $testResponse->assertDontSee(route('lans.clone.create', $lan), false);
    }
}
