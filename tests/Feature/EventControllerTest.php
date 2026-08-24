<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class EventControllerTest extends TestCase
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

        $this->lan = Lan::factory()->create([
            'start' => '2026-06-01 00:00:00',
            'end' => '2026-06-03 00:00:00',
        ]);

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);

        $this->regularUser = User::factory()->create();
    }

    private function validEventInput(array $overrides = []): array
    {
        return array_merge([
            'name' => 'My LAN Event',
            'start' => '2026-06-01 10:00',
            'end' => '2026-06-01 12:00',
        ], $overrides);
    }

    // --- store() ---

    public function test_store_with_checkbox_checked_creates_event_and_blank_notification_message(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.store', ['lan' => $this->lan]), $this->validEventInput([
                'create_default_discord_notification_message' => '1',
            ]));

        $event = Event::where('name', 'My LAN Event')->firstOrFail();
        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $event]));
        $this->assertDatabaseHas('event_discord_notification_messages', [
            'event_id' => $event->id,
            'message' => null,
        ]);
    }

    public function test_store_with_checkbox_omitted_creates_event_without_notification_message(): void
    {
        $this->actingAs($this->adminUser)
            ->post(route('lans.events.store', ['lan' => $this->lan]), $this->validEventInput());

        $event = Event::where('name', 'My LAN Event')->firstOrFail();
        $this->assertDatabaseMissing('event_discord_notification_messages', ['event_id' => $event->id]);
    }

    public function test_store_returns_403_for_non_admin_and_creates_nothing(): void
    {
        $testResponse = $this->actingAs($this->regularUser)
            ->post(route('lans.events.store', ['lan' => $this->lan]), $this->validEventInput([
                'create_default_discord_notification_message' => '1',
            ]));

        $testResponse->assertStatus(403);
        $this->assertDatabaseCount('events', 0);
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    // --- create() view ---

    public function test_create_page_shows_checkbox_checked_by_default_for_admin(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.create', ['lan' => $this->lan]));

        $testResponse->assertOk();
        $testResponse->assertSee('name="create_default_discord_notification_message"', false);
        $testResponse->assertSee(trans('title.create-default-discord-notification-message'));
    }

    // --- edit() view ---

    public function test_edit_page_does_not_show_checkbox(): void
    {
        $event = Event::factory()->create([
            'lan_id' => $this->lan->id,
            'start' => $this->lan->start,
            'end' => $this->lan->end,
        ]);
        EventDiscordNotificationMessage::factory()->create(['event_id' => $event->id]);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.edit', ['lan' => $this->lan, 'event' => $event]));

        $testResponse->assertOk();
        $testResponse->assertDontSee('name="create_default_discord_notification_message"', false);
    }
}
