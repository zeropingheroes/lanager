<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class EventPlaceholdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_placeholders_returns_event_name_and_url(): void
    {
        $lan = Lan::factory()->create();
        $event = Event::factory()->create([
            'lan_id' => $lan->id,
            'name' => 'Summer LAN 2026',
            'start' => $lan->start,
            'end' => $lan->end,
        ]);

        $placeholders = $event->placeholders();

        $this->assertSame('Summer LAN 2026', $placeholders['{{event.name}}']);
        $this->assertSame(route('lans.events.show', ['lan' => $lan, 'event' => $event]), $placeholders['{{event.url}}']);
    }
}
