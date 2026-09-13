<?php

declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class EventTest extends TestCase
{
    private function eventFor(string $eventStart, string $eventEnd, string $lanStart, string $lanEnd): Event
    {
        $lan = new Lan(['start' => $lanStart, 'end' => $lanEnd]);

        return (new Event(['start' => $eventStart, 'end' => $eventEnd]))->setRelation('lan', $lan);
    }

    public function test_is_out_of_lan_time_range_returns_true_when_event_starts_before_the_lan_starts(): void
    {
        $event = $this->eventFor(
            eventStart: '2025-06-01 17:00',
            eventEnd: '2025-06-01 19:00',
            lanStart: '2025-06-01 18:00',
            lanEnd: '2025-06-03 18:00',
        );

        $this->assertTrue($event->isOutOfLanTimeRange());
    }

    public function test_is_out_of_lan_time_range_returns_true_when_event_ends_after_the_lan_ends(): void
    {
        $event = $this->eventFor(
            eventStart: '2025-06-03 17:00',
            eventEnd: '2025-06-03 19:00',
            lanStart: '2025-06-01 18:00',
            lanEnd: '2025-06-03 18:00',
        );

        $this->assertTrue($event->isOutOfLanTimeRange());
    }

    public function test_is_out_of_lan_time_range_returns_false_when_event_is_fully_within_the_lan_time_range(): void
    {
        $event = $this->eventFor(
            eventStart: '2025-06-01 19:00',
            eventEnd: '2025-06-01 20:00',
            lanStart: '2025-06-01 18:00',
            lanEnd: '2025-06-03 18:00',
        );

        $this->assertFalse($event->isOutOfLanTimeRange());
    }

    public function test_is_out_of_lan_time_range_returns_false_when_event_start_and_end_are_exactly_on_the_lan_boundaries(): void
    {
        $event = $this->eventFor(
            eventStart: '2025-06-01 18:00',
            eventEnd: '2025-06-03 18:00',
            lanStart: '2025-06-01 18:00',
            lanEnd: '2025-06-03 18:00',
        );

        $this->assertFalse($event->isOutOfLanTimeRange());
    }
}
