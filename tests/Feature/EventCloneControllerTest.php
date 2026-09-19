<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessageImage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class EventCloneControllerTest extends TestCase
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

    private function sourceEvent(Lan $lan, array $overrides = []): Event
    {
        return Event::factory()->create(array_merge([
            'lan_id' => $lan->id,
            'name' => 'Source Event',
            'start' => Carbon::parse('2026-06-05 19:00'),
            'end' => Carbon::parse('2026-06-05 20:00'),
        ], $overrides));
    }

    private function validCloneInput(Lan $destinationLan, array $overrides = []): array
    {
        return array_merge([
            'lan_id' => $destinationLan->id,
            'name' => 'Cloned Event',
            'description' => 'Cloned description',
            'start' => $destinationLan->start->copy()->addHours(6)->format('Y-m-d H:i'),
            'end' => $destinationLan->start->copy()->addHours(8)->format('Y-m-d H:i'),
        ], $overrides);
    }

    // --- access ---

    public function test_non_admin_cannot_view_clone_form(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);

        $testResponse = $this->actingAs($this->nonAdminUser)->get(route('lans.events.clone.create', ['lan' => $lan, 'event' => $event]));

        $testResponse->assertForbidden();
    }

    public function test_non_admin_cannot_submit_clone_form(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);

        $testResponse = $this->actingAs($this->nonAdminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan)
        );

        $testResponse->assertForbidden();
        $this->assertDatabaseMissing('events', ['name' => 'Cloned Event']);
    }

    public function test_admin_can_view_the_clone_form_prefilled_with_the_sources_values(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan, [
            'name' => 'Source Event',
            'description' => 'Source description',
        ]);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.events.clone.create', ['lan' => $lan, 'event' => $event]));

        $testResponse->assertOk();
        $testResponse->assertSee('Source Event', false);
        $testResponse->assertSee('Source description', false);
    }

    // --- valid submissions ---

    public function test_admin_can_clone_an_event_to_its_own_lan(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);

        $testResponse = $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, ['name' => 'Cloned Within Same Lan'])
        );

        $newEvent = Event::where('name', 'Cloned Within Same Lan')->firstOrFail();
        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $lan, 'event' => $newEvent]));
        $this->assertSame($lan->id, $newEvent->lan_id);
        $this->assertSame('Cloned description', $newEvent->description);
    }

    public function test_admin_can_clone_an_event_to_a_different_lan(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);
        $destinationLan = $this->sourceLan(['start' => '2026-07-01 12:00', 'end' => '2026-07-03 12:00']);

        $testResponse = $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($destinationLan, ['name' => 'Cloned To Other Lan'])
        );

        $newEvent = Event::where('name', 'Cloned To Other Lan')->firstOrFail();
        $testResponse->assertRedirect(route('lans.events.show', ['lan' => $destinationLan->id, 'event' => $newEvent]));
        $this->assertSame($destinationLan->id, $newEvent->lan_id);
    }

    public function test_submission_with_out_of_range_times_is_rejected_and_creates_no_event(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);
        $eventCountBefore = Event::count();

        $testResponse = $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, [
                'name' => 'Out Of Range Clone',
                'start' => '2026-06-01 12:00',
                'end' => '2026-06-01 14:00',
            ])
        );

        $testResponse->assertRedirect();
        $this->assertDatabaseMissing('events', ['name' => 'Out Of Range Clone']);
        $this->assertSame($eventCountBefore, Event::count());
    }

    // --- published status ---

    public function test_published_status_defaults_to_the_sources_when_left_as_prefilled_published(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan, ['published' => true]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, ['name' => 'Cloned Published', 'published' => '1'])
        );

        $newEvent = Event::where('name', 'Cloned Published')->firstOrFail();
        $this->assertTrue((bool) $newEvent->published);
    }

    public function test_published_status_defaults_to_the_sources_when_left_as_prefilled_unpublished(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan, ['published' => false]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, ['name' => 'Cloned Unpublished'])
        );

        $newEvent = Event::where('name', 'Cloned Unpublished')->firstOrFail();
        $this->assertFalse((bool) $newEvent->published);
    }

    public function test_published_status_reflects_a_submitted_change_from_published_to_unpublished(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan, ['published' => true]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, ['name' => 'Unchecked Clone'])
        );

        $newEvent = Event::where('name', 'Unchecked Clone')->firstOrFail();
        $this->assertFalse((bool) $newEvent->published);
    }

    public function test_published_status_reflects_a_submitted_change_from_unpublished_to_published(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan, ['published' => false]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, ['name' => 'Checked Clone', 'published' => '1'])
        );

        $newEvent = Event::where('name', 'Checked Clone')->firstOrFail();
        $this->assertTrue((bool) $newEvent->published);
    }

    // --- source event unaffected ---

    public function test_source_event_is_unchanged_after_cloning(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan, ['name' => 'Original Name', 'description' => 'Original description', 'published' => true]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, ['name' => 'A Different Cloned Name', 'description' => 'A different description'])
        );

        $event->refresh();
        $this->assertSame('Original Name', $event->name);
        $this->assertSame('Original description', $event->description);
        $this->assertTrue((bool) $event->published);
    }

    // --- Discord notification message options ---

    public function test_clone_option_copies_the_sources_message_and_images(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);
        $message = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $event->id,
            'message' => 'Custom event message',
        ]);
        EventDiscordNotificationMessageImage::factory()->create([
            'event_discord_notification_message_id' => $message->id,
            'image_path' => 'images/one.png',
            'sort_order' => 0,
        ]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, [
                'name' => 'Cloned With Message',
                'discord_notification_message_option' => 'clone_existing',
            ])
        );

        $newEvent = Event::where('name', 'Cloned With Message')->firstOrFail();
        $clonedMessage = $newEvent->discordNotificationMessage()->firstOrFail();
        $this->assertNotSame($message->id, $clonedMessage->id);
        $this->assertSame('Custom event message', $clonedMessage->message);
        $this->assertSame(['images/one.png'], $clonedMessage->images()->pluck('image_path')->all());
    }

    public function test_default_option_creates_a_fresh_message_instead_of_cloning(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);
        EventDiscordNotificationMessage::factory()->create([
            'event_id' => $event->id,
            'message' => 'Custom event message',
        ]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, [
                'name' => 'Cloned With Default',
                'discord_notification_message_option' => 'create_default',
            ])
        );

        $newEvent = Event::where('name', 'Cloned With Default')->firstOrFail();
        $newMessage = $newEvent->discordNotificationMessage()->firstOrFail();
        $this->assertNull($newMessage->message);
    }

    public function test_none_option_creates_no_message_when_source_has_one(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);
        EventDiscordNotificationMessage::factory()->create([
            'event_id' => $event->id,
            'message' => 'Custom event message',
        ]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, [
                'name' => 'Cloned With None',
                'discord_notification_message_option' => 'none',
            ])
        );

        $newEvent = Event::where('name', 'Cloned With None')->firstOrFail();
        $this->assertNull($newEvent->discordNotificationMessage);
    }

    public function test_default_option_creates_a_message_when_source_has_none(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, [
                'name' => 'Cloned Default Selected',
                'discord_notification_message_option' => 'create_default',
            ])
        );

        $newEvent = Event::where('name', 'Cloned Default Selected')->firstOrFail();
        $this->assertNotNull($newEvent->discordNotificationMessage);
    }

    public function test_none_option_creates_no_message_when_source_has_none(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, [
                'name' => 'Cloned None Selected',
                'discord_notification_message_option' => 'none',
            ])
        );

        $newEvent = Event::where('name', 'Cloned None Selected')->firstOrFail();
        $this->assertNull($newEvent->discordNotificationMessage);
    }

    public function test_clone_option_is_ignored_gracefully_when_source_has_no_message(): void
    {
        // The "Clone existing..." radio is disabled in the UI when the source has no message,
        // but a direct submission of it should still fail gracefully rather than error.
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, [
                'name' => 'Cloned Clone Selected With No Source Message',
                'discord_notification_message_option' => 'clone_existing',
            ])
        );

        $newEvent = Event::where('name', 'Cloned Clone Selected With No Source Message')->firstOrFail();
        $this->assertNull($newEvent->discordNotificationMessage);
    }

    public function test_clone_radio_is_disabled_when_source_has_no_message(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.events.clone.create', ['lan' => $lan, 'event' => $event]));

        preg_match('/<input[^>]*id="discord_notification_message_option_clone_existing"[^>]*>/', $testResponse->getContent(), $matches);
        $this->assertNotEmpty($matches, 'Could not find the "clone" radio input in the response.');
        $this->assertStringContainsString('disabled', $matches[0]);
    }

    public function test_no_message_info_text_is_shown_when_source_has_no_message(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.events.clone.create', ['lan' => $lan, 'event' => $event]));

        preg_match('/<span[^>]*id="discord_notification_message_option_clone_existing_unavailable"[^>]*>.*?<\/span>/s', $testResponse->getContent(), $matches);
        $this->assertNotEmpty($matches, 'Could not find the "source has no message" text in the response.');
        $this->assertStringNotContainsString('badge', $matches[0]);
        $this->assertStringContainsString('fa-circle-info', $matches[0]);
        $this->assertStringContainsString('Source event has no message', $matches[0]);
    }

    public function test_no_message_info_text_is_not_shown_when_source_has_a_message(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);
        EventDiscordNotificationMessage::factory()->create(['event_id' => $event->id]);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.events.clone.create', ['lan' => $lan, 'event' => $event]));

        $testResponse->assertDontSee('Source event has no message');
    }

    public function test_clone_radio_is_enabled_and_selected_when_source_has_a_message(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);
        EventDiscordNotificationMessage::factory()->create(['event_id' => $event->id]);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.events.clone.create', ['lan' => $lan, 'event' => $event]));

        preg_match('/<input[^>]*id="discord_notification_message_option_clone_existing"[^>]*>/', $testResponse->getContent(), $matches);
        $this->assertNotEmpty($matches, 'Could not find the "clone" radio input in the response.');
        $this->assertStringNotContainsString('disabled', $matches[0]);
        $this->assertStringContainsString('checked', $matches[0]);
    }

    public function test_sources_message_is_unaffected_by_cloning(): void
    {
        $lan = $this->sourceLan();
        $event = $this->sourceEvent($lan);
        $message = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $event->id,
            'message' => 'Custom event message',
        ]);

        $this->actingAs($this->adminUser)->post(
            route('lans.events.clone.store', ['lan' => $lan, 'event' => $event]),
            $this->validCloneInput($lan, [
                'name' => 'Another Clone',
                'discord_notification_message_option' => 'clone_existing',
            ])
        );

        $message->refresh();
        $this->assertSame('Custom event message', $message->message);
    }
}
