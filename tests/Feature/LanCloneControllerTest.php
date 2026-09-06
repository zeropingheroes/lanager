<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Attendee;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessageImage;
use Zeropingheroes\Lanager\Models\EventSignup;
use Zeropingheroes\Lanager\Models\Guide;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\Slide;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserAchievement;
use Zeropingheroes\Lanager\Models\Venue;

class LanCloneControllerTest extends TestCase
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

    // --- access (1.2) ---

    public function test_non_admin_cannot_view_clone_form(): void
    {
        $lan = $this->sourceLan();

        $testResponse = $this->actingAs($this->nonAdminUser)->get(route('lans.clone.create', $lan));

        $testResponse->assertForbidden();
    }

    public function test_non_admin_cannot_submit_clone_form(): void
    {
        $lan = $this->sourceLan();

        $testResponse = $this->actingAs($this->nonAdminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'New LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
        ]);

        $testResponse->assertForbidden();
        $this->assertDatabaseMissing('lans', ['name' => 'New LAN']);
    }

    public function test_admin_can_view_clone_form_with_source_lans_items(): void
    {
        $lan = $this->sourceLan();
        $guide = Guide::create(['lan_id' => $lan->id, 'title' => 'Venue Guide', 'content' => 'x', 'published' => true]);
        $event = Event::factory()->create(['lan_id' => $lan->id, 'name' => 'Overwatch', 'start' => $lan->start->copy()->addHours(6), 'end' => $lan->start->copy()->addHours(9)]);
        $slide = Slide::create(['lan_id' => $lan->id, 'name' => 'Sponsor Slide', 'content' => 'x', 'position' => 0, 'duration' => 10, 'published' => true, 'start' => null, 'end' => null]);
        $webhook = DiscordChannelWebhook::factory()->create(['lan_id' => $lan->id, 'purpose' => 'live']);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.clone.create', $lan));

        $testResponse->assertOk();
        $testResponse->assertSee($guide->title);
        $testResponse->assertSee($event->name);
        $testResponse->assertSee($slide->name);
        $testResponse->assertSee($webhook->purpose);
    }

    // --- new LAN validation reuse ---

    public function test_overlapping_dates_are_rejected_and_nothing_is_created(): void
    {
        $lan = $this->sourceLan();
        $existingLan = Lan::factory()->create(['start' => '2026-08-01 12:00', 'end' => '2026-08-03 12:00']);
        $lanCountBefore = Lan::count();

        $testResponse = $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Overlapping LAN',
            'start' => '2026-08-02 12:00',
            'end' => '2026-08-04 12:00',
        ]);

        $testResponse->assertRedirect();
        $this->assertDatabaseMissing('lans', ['name' => 'Overlapping LAN']);
        $this->assertSame($lanCountBefore, Lan::count());
    }

    public function test_valid_submission_creates_exactly_one_new_unpublished_lan(): void
    {
        $lan = $this->sourceLan();

        $testResponse = $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $testResponse->assertRedirect(route('lans.events.index', $newLan));
        $this->assertFalse((bool) $newLan->published);
    }

    public function test_venue_and_achievement_ids_submitted_as_strings_are_accepted(): void
    {
        // Real form submissions send every field as a string, unlike Pest's typed array payloads.
        $lan = $this->sourceLan();
        $venue = Venue::factory()->create();
        $achievement = Achievement::create(['name' => 'Attendance', 'description' => 'Signed in']);

        $testResponse = $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'venue_id' => (string) $venue->id,
            'achievement_id' => (string) $achievement->id,
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $testResponse->assertRedirect(route('lans.events.index', $newLan));
        $this->assertSame($venue->id, $newLan->venue_id);
        $this->assertSame($achievement->id, $newLan->achievement_id);
    }

    // --- ID scoping ---

    public function test_an_id_belonging_to_a_different_lan_is_not_cloned(): void
    {
        $lan = $this->sourceLan();
        $otherLan = Lan::factory()->create(['start' => '2026-09-01 12:00', 'end' => '2026-09-03 12:00']);
        $foreignGuide = Guide::create(['lan_id' => $otherLan->id, 'title' => 'Foreign Guide', 'content' => 'x', 'published' => true]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'guide_ids' => [$foreignGuide->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $this->assertSame(0, $newLan->guides()->count());
    }

    // --- Guides ---

    public function test_selected_guide_is_cloned_unpublished_regardless_of_source_published_state(): void
    {
        $lan = $this->sourceLan();
        $guide = Guide::create(['lan_id' => $lan->id, 'title' => 'Wifi Guide', 'content' => 'Connect to **LAN** wifi', 'published' => true]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'guide_ids' => [$guide->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $this->assertDatabaseHas('guides', [
            'lan_id' => $newLan->id,
            'title' => 'Wifi Guide',
            'content' => 'Connect to **LAN** wifi',
            'published' => false,
        ]);
    }

    public function test_deselected_guide_is_not_cloned(): void
    {
        $lan = $this->sourceLan();
        Guide::create(['lan_id' => $lan->id, 'title' => 'Wifi Guide', 'content' => 'x', 'published' => true]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'guide_ids' => [],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $this->assertSame(0, $newLan->guides()->count());
    }

    // --- Events: timing, overflow, unpublished, signup window ---

    public function test_selected_event_is_cloned_with_timing_offset_preserved_and_unpublished(): void
    {
        $lan = $this->sourceLan(); // start 2026-06-05 12:00, end 2026-06-07 12:00
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'name' => 'Overwatch',
            'start' => Carbon::parse('2026-06-05 18:00'), // +6h from lan start
            'end' => Carbon::parse('2026-06-05 21:00'), // +9h from lan start, 3h duration
            'published' => true,
        ]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00', // new lan start
            'end' => '2026-07-03 12:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->where('name', 'Overwatch')->firstOrFail();

        $this->assertTrue($clonedEvent->start->equalTo(Carbon::parse('2026-07-01 18:00')));
        $this->assertTrue($clonedEvent->end->equalTo(Carbon::parse('2026-07-01 21:00')));
        $this->assertFalse((bool) $clonedEvent->published);
    }

    public function test_event_shifted_outside_a_shorter_new_lan_is_still_created(): void
    {
        $lan = $this->sourceLan(); // 2026-06-05 12:00 to 2026-06-07 12:00 (48h)
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'name' => 'The Ship',
            'start' => Carbon::parse('2026-06-07 06:00'), // +42h from lan start
            'end' => Carbon::parse('2026-06-07 09:00'),
        ]);

        // New LAN is only 6 hours long - the shifted event (+42h to +45h) will fall outside it.
        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Short LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-01 18:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Short LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->where('name', 'The Ship')->firstOrFail();

        $this->assertTrue($clonedEvent->start->greaterThan($newLan->end));
    }

    public function test_event_timing_shifts_correctly_into_a_longer_new_lan(): void
    {
        $lan = $this->sourceLan(); // 2026-06-05 12:00 to 2026-06-07 12:00 (48h)
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'name' => 'Overwatch',
            'start' => Carbon::parse('2026-06-05 18:00'), // +6h
            'end' => Carbon::parse('2026-06-05 21:00'), // +9h, 3h duration
        ]);

        // New LAN is a full week long - longer than the source.
        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Long LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-08 12:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Long LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->where('name', 'Overwatch')->firstOrFail();

        $this->assertTrue($clonedEvent->start->equalTo(Carbon::parse('2026-07-01 18:00')));
        $this->assertTrue($clonedEvent->end->equalTo(Carbon::parse('2026-07-01 21:00')));
    }

    public function test_event_time_of_day_is_preserved_when_new_lan_starts_at_a_different_time(): void
    {
        $lan = $this->sourceLan(); // start 2026-06-05 12:00 (source LAN's start date)
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'name' => 'Overwatch',
            'start' => Carbon::parse('2026-06-06 18:00'), // 1 day after the source LAN's start date, at 18:00
            'end' => Carbon::parse('2026-06-06 21:00'), // same day, at 21:00
        ]);

        // New LAN starts at a different time of day (09:00, not 12:00).
        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 09:00',
            'end' => '2026-07-03 09:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->where('name', 'Overwatch')->firstOrFail();

        // The date shifts by 1 day (matching the event's date offset from the source LAN's start
        // date), but the time of day (18:00/21:00) is unaffected by the new LAN's 09:00 start time.
        $this->assertTrue($clonedEvent->start->equalTo(Carbon::parse('2026-07-02 18:00')));
        $this->assertTrue($clonedEvent->end->equalTo(Carbon::parse('2026-07-02 21:00')));
    }

    public function test_events_signup_window_shifts_with_the_event(): void
    {
        $lan = $this->sourceLan();
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'start' => Carbon::parse('2026-06-06 18:00'), // +30h
            'end' => Carbon::parse('2026-06-06 21:00'),
            'signups_open' => Carbon::parse('2026-06-05 12:00'), // +0h (= lan start)
            'signups_close' => Carbon::parse('2026-06-06 12:00'), // +24h
        ]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->firstOrFail();

        $this->assertTrue($clonedEvent->signups_open->equalTo(Carbon::parse('2026-07-01 12:00')));
        $this->assertTrue($clonedEvent->signups_close->equalTo(Carbon::parse('2026-07-02 12:00')));
    }

    public function test_events_without_a_signup_window_stay_unset_when_cloned(): void
    {
        $lan = $this->sourceLan();
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'start' => Carbon::parse('2026-06-06 18:00'),
            'end' => Carbon::parse('2026-06-06 21:00'),
            'signups_open' => null,
            'signups_close' => null,
        ]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->firstOrFail();

        $this->assertNull($clonedEvent->signups_open);
        $this->assertNull($clonedEvent->signups_close);
    }

    // --- Slides ---

    public function test_scheduled_slide_timing_shifts_and_unscheduled_slide_stays_unscheduled(): void
    {
        $lan = $this->sourceLan();
        $scheduledSlide = Slide::create([
            'lan_id' => $lan->id,
            'name' => 'Scheduled Slide',
            'content' => 'x',
            'position' => 0,
            'duration' => 10,
            'published' => true,
            'start' => Carbon::parse('2026-06-05 18:00'), // +6h
            'end' => Carbon::parse('2026-06-06 18:00'), // +30h
        ]);
        $unscheduledSlide = Slide::create([
            'lan_id' => $lan->id,
            'name' => 'Unscheduled Slide',
            'content' => 'x',
            'position' => 1,
            'duration' => 10,
            'published' => true,
            'start' => null,
            'end' => null,
        ]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'slide_ids' => [$scheduledSlide->id, $unscheduledSlide->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();

        $clonedScheduled = $newLan->slides()->where('name', 'Scheduled Slide')->firstOrFail();
        $this->assertTrue($clonedScheduled->start->equalTo(Carbon::parse('2026-07-01 18:00')));
        $this->assertTrue($clonedScheduled->end->equalTo(Carbon::parse('2026-07-02 18:00')));
        $this->assertFalse((bool) $clonedScheduled->published);

        $clonedUnscheduled = $newLan->slides()->where('name', 'Unscheduled Slide')->firstOrFail();
        $this->assertNull($clonedUnscheduled->start);
        $this->assertNull($clonedUnscheduled->end);
    }

    // --- Discord notification messages & images ---

    public function test_events_discord_notification_message_and_images_are_cloned_with_automatically_sent_at_cleared(): void
    {
        $lan = $this->sourceLan();
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'start' => $lan->start->copy()->addHours(6),
            'end' => $lan->start->copy()->addHours(9),
        ]);
        $message = EventDiscordNotificationMessage::factory()->sent()->create([
            'event_id' => $event->id,
            'message' => 'Custom event message',
            'automatic' => true,
        ]);
        $imageOne = EventDiscordNotificationMessageImage::factory()->create([
            'event_discord_notification_message_id' => $message->id,
            'image_path' => 'images/one.png',
            'sort_order' => 0,
        ]);
        $imageTwo = EventDiscordNotificationMessageImage::factory()->create([
            'event_discord_notification_message_id' => $message->id,
            'image_path' => 'images/two.png',
            'sort_order' => 1,
        ]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->firstOrFail();
        $clonedMessage = $clonedEvent->discordNotificationMessage()->firstOrFail();

        $this->assertSame('Custom event message', $clonedMessage->message);
        $this->assertTrue($clonedMessage->automatic);
        $this->assertNull($clonedMessage->automatically_sent_at);

        $clonedImages = $clonedMessage->images()->orderBy('sort_order')->pluck('image_path')->all();
        $this->assertSame(['images/one.png', 'images/two.png'], $clonedImages);
    }

    public function test_event_without_a_notification_message_clones_with_none(): void
    {
        $lan = $this->sourceLan();
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'start' => $lan->start->copy()->addHours(6),
            'end' => $lan->start->copy()->addHours(9),
        ]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->firstOrFail();

        $this->assertNull($clonedEvent->discordNotificationMessage);
    }

    // --- Discord channel webhooks ---

    public function test_only_selected_webhooks_are_cloned_to_the_new_lan(): void
    {
        $lan = $this->sourceLan();
        $selectedWebhook = DiscordChannelWebhook::factory()->create(['lan_id' => $lan->id, 'purpose' => 'live']);
        DiscordChannelWebhook::factory()->create(['lan_id' => $lan->id, 'purpose' => 'test']);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'webhook_ids' => [$selectedWebhook->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();

        $this->assertSame(1, $newLan->discordChannelWebhooks()->count());
        $this->assertDatabaseHas('discord_channel_webhooks', [
            'lan_id' => $newLan->id,
            'purpose' => 'live',
            'webhook_url' => $selectedWebhook->webhook_url,
        ]);
    }

    // --- Excluded data ---

    public function test_participation_data_is_never_cloned(): void
    {
        $lan = $this->sourceLan();
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'start' => $lan->start->copy()->addHours(6),
            'end' => $lan->start->copy()->addHours(9),
        ]);
        $attendee = User::factory()->create();
        $achievement = Achievement::create(['name' => 'Attendance', 'description' => 'Signed in']);
        EventSignup::create(['event_id' => $event->id, 'user_id' => $attendee->id]);
        LanGame::create(['lan_id' => $lan->id, 'game_name' => 'Quake', 'created_by' => $attendee->id]);
        Attendee::create(['lan_id' => $lan->id, 'user_id' => $attendee->id]);
        UserAchievement::create(['lan_id' => $lan->id, 'user_id' => $attendee->id, 'achievement_id' => $achievement->id]);

        $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
            'event_ids' => [$event->id],
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $clonedEvent = $newLan->events()->firstOrFail();

        $this->assertSame(0, $clonedEvent->signups()->count());
        $this->assertSame(0, $newLan->games()->count());
        $this->assertSame(0, $newLan->users()->count());
        $this->assertSame(0, $newLan->userAchievements()->count());
    }

    // --- Confirmation ---

    public function test_successful_clone_flashes_a_success_message_and_redirects_to_the_new_lan(): void
    {
        $lan = $this->sourceLan();

        $testResponse = $this->actingAs($this->adminUser)->post(route('lans.clone.store', $lan), [
            'name' => 'Cloned LAN',
            'start' => '2026-07-01 12:00',
            'end' => '2026-07-03 12:00',
        ]);

        $newLan = Lan::where('name', 'Cloned LAN')->firstOrFail();
        $testResponse->assertRedirect(route('lans.events.index', $newLan));
        $testResponse->assertSessionHas('success', trans('phrase.successfully-cloned-lan', ['sourceLanName' => $lan->name]));
    }
}
