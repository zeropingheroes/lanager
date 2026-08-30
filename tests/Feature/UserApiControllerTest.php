<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;

class UserApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_show_always_includes_roles(): void
    {
        // The first user created is auto-assigned the super-admin role (see UserObserver).
        $user = User::factory()->create();
        $role = $user->roles()->firstOrFail();

        $testResponse = $this->getJson(route('api.v1.users.show', $user));

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.roles.0.id', $role->id);
        $testResponse->assertJsonPath('data.roles.0.name', $role->name);
        $testResponse->assertJsonPath('data.roles.0.display_name', $role->display_name);
    }

    public function test_show_includes_empty_roles_array_when_user_has_none(): void
    {
        // Create a throwaway first user so the auto-super-admin assignment (see UserObserver)
        // lands on them instead of the user under test.
        User::factory()->create();
        $user = User::factory()->create();

        $testResponse = $this->getJson(route('api.v1.users.show', $user));

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.roles', []);
    }

    public function test_show_omits_lans_when_not_requested(): void
    {
        $user = User::factory()->create();

        $testResponse = $this->getJson(route('api.v1.users.show', $user));

        $testResponse->assertOk();
        $testResponse->assertJsonMissingPath('data.lans');
    }

    public function test_show_includes_lans_when_requested(): void
    {
        $user = User::factory()->create();
        $lan = Lan::factory()->create();
        $user->lans()->attach($lan);

        $testResponse = $this->getJson(route('api.v1.users.show', $user).'?lans');

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.lans.0.id', $lan->id);
    }

    public function test_show_includes_empty_lans_array_when_user_has_attended_none(): void
    {
        $user = User::factory()->create();

        $testResponse = $this->getJson(route('api.v1.users.show', $user).'?lans');

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.lans', []);
    }
}
