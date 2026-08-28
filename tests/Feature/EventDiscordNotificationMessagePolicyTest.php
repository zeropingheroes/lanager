<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Policies\EventDiscordNotificationMessagePolicy;

class EventDiscordNotificationMessagePolicyTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private EventDiscordNotificationMessagePolicy $eventDiscordNotificationMessagePolicy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->eventDiscordNotificationMessagePolicy = new EventDiscordNotificationMessagePolicy;
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $roleModel = Role::where('name', $role)->firstOrFail();
        $user->roles()->attach($roleModel->id, ['assigned_by' => $user->id]);

        return $user->fresh();
    }

    public function test_admin_can_update_send_and_preview(): void
    {
        $user = $this->userWithRole('admin');

        $this->assertTrue($this->eventDiscordNotificationMessagePolicy->update($user));
        $this->assertTrue($this->eventDiscordNotificationMessagePolicy->send($user));
        $this->assertTrue($this->eventDiscordNotificationMessagePolicy->preview($user));
    }

    public function test_super_admin_can_update_send_and_preview(): void
    {
        $user = $this->userWithRole('super-admin');

        $this->assertTrue(Gate::forUser($user)->allows('update', EventDiscordNotificationMessage::class));
        $this->assertTrue(Gate::forUser($user)->allows('send', EventDiscordNotificationMessage::class));
        $this->assertTrue(Gate::forUser($user)->allows('preview', EventDiscordNotificationMessage::class));
    }

    public function test_regular_user_cannot_update_send_or_preview(): void
    {
        $regularUser = User::factory()->create();

        $this->assertFalse($this->eventDiscordNotificationMessagePolicy->update($regularUser));
        $this->assertFalse($this->eventDiscordNotificationMessagePolicy->send($regularUser));
        $this->assertFalse($this->eventDiscordNotificationMessagePolicy->preview($regularUser));
    }
}
