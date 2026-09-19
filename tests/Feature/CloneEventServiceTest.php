<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessageImage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Services\CloneEventService;

class CloneEventServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function sourceEvent(array $overrides = []): Event
    {
        $lan = Lan::factory()->create([
            'start' => Carbon::parse('2026-06-05 12:00'),
            'end' => Carbon::parse('2026-06-07 12:00'),
        ]);

        return Event::factory()->create(array_merge([
            'lan_id' => $lan->id,
            'name' => 'Source Event',
            'description' => 'Source description',
            'start' => Carbon::parse('2026-06-05 18:00'),
            'end' => Carbon::parse('2026-06-05 20:00'),
            'published' => true,
        ], $overrides));
    }

    // --- cloning + overriding fields ---

    public function test_cloning_without_overrides_copies_the_sources_own_fields(): void
    {
        $event = $this->sourceEvent();

        $newEvent = (new CloneEventService)->clone($event);

        $this->assertNotSame($event->id, $newEvent->id);
        $this->assertSame($event->lan_id, $newEvent->lan_id);
        $this->assertSame($event->name, $newEvent->name);
        $this->assertSame($event->description, $newEvent->description);
        $this->assertTrue($newEvent->start->equalTo($event->start));
        $this->assertTrue($newEvent->end->equalTo($event->end));
        $this->assertSame($event->published, $newEvent->published);
    }

    public function test_overrides_take_precedence_over_the_sources_own_fields(): void
    {
        $event = $this->sourceEvent(['published' => true]);
        $destinationLan = Lan::factory()->create(['start' => '2026-07-01 12:00', 'end' => '2026-07-03 12:00']);

        $newEvent = (new CloneEventService)->clone($event, [
            'lan_id' => $destinationLan->id,
            'name' => 'Overridden Name',
            'published' => false,
        ]);

        $this->assertSame($destinationLan->id, $newEvent->lan_id);
        $this->assertSame('Overridden Name', $newEvent->name);
        $this->assertFalse((bool) $newEvent->published);
        // Fields not overridden still come from the source event
        $this->assertSame($event->description, $newEvent->description);
    }

    public function test_source_event_is_unchanged_after_cloning(): void
    {
        $event = $this->sourceEvent(['name' => 'Original Name']);

        (new CloneEventService)->clone($event, ['name' => 'A Different Name']);

        $event->refresh();
        $this->assertSame('Original Name', $event->name);
    }

    // --- Discord notification message options ---

    public function test_clone_existing_option_copies_the_sources_message_and_images_independently(): void
    {
        $event = $this->sourceEvent();
        $message = EventDiscordNotificationMessage::factory()->sent()->create([
            'event_id' => $event->id,
            'message' => 'Custom event message',
            'automatic' => true,
        ]);
        EventDiscordNotificationMessageImage::factory()->create([
            'event_discord_notification_message_id' => $message->id,
            'image_path' => 'images/one.png',
            'sort_order' => 0,
        ]);
        EventDiscordNotificationMessageImage::factory()->create([
            'event_discord_notification_message_id' => $message->id,
            'image_path' => 'images/two.png',
            'sort_order' => 1,
        ]);

        $newEvent = (new CloneEventService)->clone($event, [], 'clone_existing');

        $model = $newEvent->discordNotificationMessage()->firstOrFail();
        $this->assertNotSame($message->id, $model->id);
        $this->assertSame('Custom event message', $model->message);
        $this->assertTrue($model->automatic);
        $this->assertNull($model->automatically_sent_at);

        $clonedImages = $model->images()->orderBy('sort_order')->pluck('image_path')->all();
        $this->assertSame(['images/one.png', 'images/two.png'], $clonedImages);
    }

    public function test_clone_existing_option_on_a_source_with_no_message_creates_no_message(): void
    {
        $event = $this->sourceEvent();

        $newEvent = (new CloneEventService)->clone($event, [], 'clone_existing');

        $this->assertNull($newEvent->discordNotificationMessage);
    }

    public function test_create_default_option_creates_a_fresh_message_instead_of_a_copy(): void
    {
        $event = $this->sourceEvent();
        EventDiscordNotificationMessage::factory()->create([
            'event_id' => $event->id,
            'message' => 'Custom event message',
        ]);

        $newEvent = (new CloneEventService)->clone($event, [], 'create_default');

        $model = $newEvent->discordNotificationMessage()->firstOrFail();
        $this->assertNull($model->message);
    }

    public function test_none_option_creates_no_message_even_when_source_has_one(): void
    {
        $event = $this->sourceEvent();
        EventDiscordNotificationMessage::factory()->create(['event_id' => $event->id]);

        $newEvent = (new CloneEventService)->clone($event, [], 'none');

        $this->assertNull($newEvent->discordNotificationMessage);
    }

    public function test_no_option_creates_no_message_even_when_source_has_one(): void
    {
        $event = $this->sourceEvent();
        EventDiscordNotificationMessage::factory()->create(['event_id' => $event->id]);

        $newEvent = (new CloneEventService)->clone($event);

        $this->assertNull($newEvent->discordNotificationMessage);
    }

    public function test_sources_message_is_unaffected_by_cloning(): void
    {
        $event = $this->sourceEvent();
        $message = EventDiscordNotificationMessage::factory()->create([
            'event_id' => $event->id,
            'message' => 'Custom event message',
        ]);

        (new CloneEventService)->clone($event, [], 'clone_existing');

        $message->refresh();
        $this->assertSame('Custom event message', $message->message);
    }
}
