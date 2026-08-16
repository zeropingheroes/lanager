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

class EventDiscordNotificationMessageControllerCrudTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $adminUser;

    private User $regularUser;

    private Lan $lan;

    private Event $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->lan = Lan::factory()->create();

        $this->event = Event::factory()->create([
            'lan_id' => $this->lan->id,
            'start' => $this->lan->start,
            'end' => $this->lan->end,
        ]);

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);

        $this->regularUser = User::factory()->create();
    }

    // --- store() ---

    public function test_store_creates_record_with_message_and_automatic_true(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Counter-Strike 2 starts now!',
                'automatic' => '1',
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $testResponse->assertSessionHas('success');
        $this->assertDatabaseHas('event_discord_notification_messages', [
            'event_id' => $this->event->id,
            'message' => 'Counter-Strike 2 starts now!',
            'automatic' => true,
        ]);
    }

    public function test_store_with_automatic_absent_sets_automatic_false(): void
    {
        $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Counter-Strike 2 starts now!',
                // automatic absent
            ]);

        $this->assertDatabaseHas('event_discord_notification_messages', [
            'event_id' => $this->event->id,
            'automatic' => false,
        ]);
    }

    public function test_store_rejects_empty_message(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => '',
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_store_rejects_message_exceeding_2000_characters(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => str_repeat('a', 2001),
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_store_returns_404_when_event_belongs_to_different_lan(): void
    {
        $otherLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $otherLan, 'event' => $this->event]), [
                'message' => 'Test',
            ]);

        $testResponse->assertStatus(404);
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_store_returns_403_for_non_admin(): void
    {
        $testResponse = $this->actingAs($this->regularUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Test',
            ]);

        $testResponse->assertStatus(403);
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    // --- edit() ---

    public function test_edit_returns_view_prefilled_with_existing_record(): void
    {
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'Existing message',
            'automatic' => false,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.edit', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertSee($notification->message);
        $testResponse->assertViewHas('event', fn ($e) => $e->discordNotificationMessage->automatic === false);
    }

    // --- update() ---

    public function test_update_modifies_existing_record(): void
    {
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'Old message',
            'automatic' => true,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->put(route('lans.events.discord-notification-message.update', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Updated message',
                // automatic absent
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $testResponse->assertSessionHas('success');
        $this->assertSame('Updated message', $notification->fresh()->message);
        $this->assertFalse($notification->fresh()->automatic);
        $this->assertDatabaseCount('event_discord_notification_messages', 1);
    }

    public function test_update_rejects_message_exceeding_2000_characters(): void
    {
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'Original',
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->put(route('lans.events.discord-notification-message.update', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => str_repeat('a', 2001),
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertSame('Original', $notification->fresh()->message);
    }

    public function test_update_returns_404_when_event_belongs_to_different_lan(): void
    {
        EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);
        $otherLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)
            ->put(route('lans.events.discord-notification-message.update', ['lan' => $otherLan, 'event' => $this->event]), [
                'message' => 'Test',
            ]);

        $testResponse->assertStatus(404);
    }

    public function test_update_returns_403_for_non_admin(): void
    {
        EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);

        $testResponse = $this->actingAs($this->regularUser)
            ->put(route('lans.events.discord-notification-message.update', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Test',
            ]);

        $testResponse->assertStatus(403);
    }

    // --- destroy() ---

    public function test_destroy_deletes_record(): void
    {
        EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);

        $testResponse = $this->actingAs($this->adminUser)
            ->delete(route('lans.events.discord-notification-message.destroy', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $testResponse->assertSessionHas('success');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_destroy_returns_404_when_event_belongs_to_different_lan(): void
    {
        EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);
        $otherLan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)
            ->delete(route('lans.events.discord-notification-message.destroy', ['lan' => $otherLan, 'event' => $this->event]));

        $testResponse->assertStatus(404);
        $this->assertDatabaseCount('event_discord_notification_messages', 1);
    }

    public function test_destroy_returns_403_for_non_admin(): void
    {
        EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);

        $testResponse = $this->actingAs($this->regularUser)
            ->delete(route('lans.events.discord-notification-message.destroy', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertStatus(403);
        $this->assertDatabaseCount('event_discord_notification_messages', 1);
    }
}
