<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessageImage;
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

    public function test_store_accepts_empty_message_and_stores_null(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => '',
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $testResponse->assertSessionHas('success');
        $this->assertDatabaseHas('event_discord_notification_messages', [
            'event_id' => $this->event->id,
            'message' => null,
        ]);
    }

    public function test_store_discards_message_matching_the_system_default_and_stores_null(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => trans('phrase.default-event-discord-notification-message'),
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $this->assertDatabaseHas('event_discord_notification_messages', [
            'event_id' => $this->event->id,
            'message' => null,
        ]);
    }

    public function test_store_discards_message_matching_the_lan_default_and_stores_null(): void
    {
        $this->lan->update(['default_event_discord_notification_message' => 'LAN default message']);

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'LAN default message',
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $this->assertDatabaseHas('event_discord_notification_messages', [
            'event_id' => $this->event->id,
            'message' => null,
        ]);
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

    public function test_update_accepts_empty_message_and_stores_null(): void
    {
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'Old message',
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->put(route('lans.events.discord-notification-message.update', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => '',
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $testResponse->assertSessionHas('success');
        $this->assertNull($notification->fresh()->message);
    }

    public function test_update_discards_message_matching_the_default_and_stores_null(): void
    {
        $notification = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => 'Old message',
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->put(route('lans.events.discord-notification-message.update', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => trans('phrase.default-event-discord-notification-message'),
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $this->assertNull($notification->fresh()->message);
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

    // --- image_paths validation ---

    public function test_store_rejects_more_than_10_image_paths(): void
    {
        Storage::fake('public');
        $paths = [];
        for ($i = 0; $i < 11; $i++) {
            $path = "images/img{$i}.png";
            Storage::disk('public')->put($path, 'data');
            $paths[] = $path;
        }

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Test message',
                'image_paths' => $paths,
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_store_rejects_image_path_outside_library_directory(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('other/hack.png', 'data');

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Test message',
                'image_paths' => ['other/hack.png'],
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_store_rejects_single_image_exceeding_10mb(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/big.png', str_repeat('x', 11 * 1024 * 1024));

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Test message',
                'image_paths' => ['images/big.png'],
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_store_rejects_total_image_size_exceeding_25mb(): void
    {
        Storage::fake('public');
        // Two files of 14 MB each = 28 MB total
        Storage::disk('public')->put('images/big1.png', str_repeat('x', 14 * 1024 * 1024));
        Storage::disk('public')->put('images/big2.png', str_repeat('x', 14 * 1024 * 1024));

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Test message',
                'image_paths' => ['images/big1.png', 'images/big2.png'],
            ]);

        $testResponse->assertRedirect();
        $testResponse->assertSessionHas('error');
        $this->assertDatabaseCount('event_discord_notification_messages', 0);
    }

    public function test_store_accepts_valid_image_paths_and_creates_image_records(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/first.png', 'png-data');
        Storage::disk('public')->put('images/second.jpg', 'jpg-data');

        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Test message',
                'image_paths' => ['images/first.png', 'images/second.jpg'],
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $testResponse->assertSessionHas('success');

        $notification = EventDiscordNotificationMessage::where('event_id', $this->event->id)->firstOrFail();
        $this->assertDatabaseHas('event_discord_notification_message_images', [
            'event_discord_notification_message_id' => $notification->id,
            'image_path' => 'images/first.png',
            'sort_order' => 0,
        ]);
        $this->assertDatabaseHas('event_discord_notification_message_images', [
            'event_discord_notification_message_id' => $notification->id,
            'image_path' => 'images/second.jpg',
            'sort_order' => 1,
        ]);
    }

    public function test_update_replaces_image_records_atomically(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/new.png', 'png-data');

        $notification = EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);
        EventDiscordNotificationMessageImage::create([
            'event_discord_notification_message_id' => $notification->id,
            'image_path' => 'images/old.png',
            'sort_order' => 0,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->put(route('lans.events.discord-notification-message.update', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Updated message',
                'image_paths' => ['images/new.png'],
            ]);

        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $this->lan, 'event' => $this->event]));
        $this->assertDatabaseMissing('event_discord_notification_message_images', [
            'event_discord_notification_message_id' => $notification->id,
            'image_path' => 'images/old.png',
        ]);
        $this->assertDatabaseHas('event_discord_notification_message_images', [
            'event_discord_notification_message_id' => $notification->id,
            'image_path' => 'images/new.png',
            'sort_order' => 0,
        ]);
    }

    public function test_store_with_no_image_paths_creates_no_image_records(): void
    {
        $this->actingAs($this->adminUser)
            ->post(route('lans.events.discord-notification-message.store', ['lan' => $this->lan, 'event' => $this->event]), [
                'message' => 'Test message',
            ]);

        $this->assertDatabaseCount('event_discord_notification_message_images', 0);
    }

    // --- create/edit view prop data ---

    public function test_create_view_passes_enriched_available_images_filtered_to_discord_extensions(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/photo.png', 'png-data');
        Storage::disk('public')->put('images/anim.gif', 'gif-data');
        Storage::disk('public')->put('images/unsupported.bmp', 'bmp-data');

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.create', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertSee('"filename":"photo.png"', false);
        $testResponse->assertSee('"filename":"anim.gif"', false);
        $testResponse->assertDontSee('"filename":"unsupported.bmp"', false);
        $testResponse->assertSee('"size":', false);
        $testResponse->assertSee('"url":', false);
    }

    public function test_edit_view_passes_enriched_selected_and_available_images(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/selected.png', 'png-data');
        Storage::disk('public')->put('images/available.jpg', 'jpg-data');

        $notification = EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);
        EventDiscordNotificationMessageImage::create([
            'event_discord_notification_message_id' => $notification->id,
            'image_path' => 'images/selected.png',
            'sort_order' => 0,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.edit', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertSee('"filename":"selected.png"', false);
        $testResponse->assertSee('"filename":"available.jpg"', false);
    }

    public function test_create_view_shows_placeholder_variables_help_text(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.create', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertSee('{{event.name}}', false);
        $testResponse->assertSee('{{event.url}}', false);
    }

    public function test_edit_view_shows_placeholder_variables_help_text(): void
    {
        EventDiscordNotificationMessage::factory()->create(['event_id' => $this->event->id]);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.edit', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertSee('{{event.name}}', false);
        $testResponse->assertSee('{{event.url}}', false);
    }

    public function test_create_view_message_field_placeholder_is_the_default_message(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.create', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertSee(
            'placeholder="'.e(trans('phrase.default-event-discord-notification-message')).'"',
            false
        );
    }

    public function test_create_view_message_field_placeholder_is_the_lan_default_message_when_set(): void
    {
        $this->lan->update(['default_event_discord_notification_message' => 'LAN default message']);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.create', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertSee('placeholder="LAN default message"', false);
    }

    public function test_create_view_prefills_message_field_with_system_default_when_lan_has_no_value(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.create', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $this->assertSame(
            2,
            substr_count($testResponse->getContent(), trans('phrase.default-event-discord-notification-message')),
            'Expected the default message to appear as both the placeholder and the pre-filled textarea value.'
        );
    }

    public function test_create_view_prefills_message_field_with_lan_default_when_lan_has_one(): void
    {
        $this->lan->update(['default_event_discord_notification_message' => 'LAN default message']);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.create', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $this->assertSame(
            2,
            substr_count($testResponse->getContent(), 'LAN default message'),
            'Expected the LAN default message to appear as both the placeholder and the pre-filled textarea value.'
        );
    }

    public function test_edit_view_prefills_message_field_with_default_when_no_existing_message(): void
    {
        EventDiscordNotificationMessage::factory()->create([
            'event_id' => $this->event->id,
            'message' => null,
        ]);

        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.edit', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $this->assertSame(
            2,
            substr_count($testResponse->getContent(), trans('phrase.default-event-discord-notification-message')),
            'Expected the default message to appear as both the placeholder and the pre-filled textarea value.'
        );
    }

    public function test_create_view_shows_help_text_linking_to_lan_edit_page(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->get(route('lans.events.discord-notification-message.create', ['lan' => $this->lan, 'event' => $this->event]));

        $testResponse->assertOk();
        $testResponse->assertSee('href="'.route('lans.edit', ['lan' => $this->lan]).'"', false);
        $testResponse->assertSee('Edit the LAN\'s default message', false);
    }
}
