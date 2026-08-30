<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class EventApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_index_returns_published_events_for_the_lan(): void
    {
        $lan = Lan::factory()->create(['published' => true]);
        $event = Event::factory()->create(['lan_id' => $lan->id, 'published' => true, 'start' => now(), 'end' => now()->addHour()]);

        $testResponse = $this->getJson(route('api.v1.lans.events.index', $lan));

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.0.id', $event->id);
    }

    public function test_index_excludes_unpublished_events(): void
    {
        $lan = Lan::factory()->create(['published' => true]);
        Event::factory()->create(['lan_id' => $lan->id, 'published' => false, 'start' => now(), 'end' => now()->addHour()]);

        $testResponse = $this->getJson(route('api.v1.lans.events.index', $lan));

        $testResponse->assertOk();
        $testResponse->assertJsonCount(0, 'data');
    }

    public function test_index_returns_404_when_lan_is_unpublished(): void
    {
        $lan = Lan::factory()->create(['published' => false]);

        $testResponse = $this->getJson(route('api.v1.lans.events.index', $lan));

        $testResponse->assertNotFound();
    }

    public function test_show_returns_the_event(): void
    {
        $lan = Lan::factory()->create(['published' => true]);
        $event = Event::factory()->create(['lan_id' => $lan->id, 'published' => true, 'start' => now(), 'end' => now()->addHour()]);

        $testResponse = $this->getJson(route('api.v1.lans.events.show', [$lan, $event]));

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.id', $event->id);
    }

    public function test_show_returns_404_when_event_does_not_belong_to_lan(): void
    {
        $lan = Lan::factory()->create(['published' => true]);
        $otherLan = Lan::factory()->create(['published' => true]);
        $event = Event::factory()->create(['lan_id' => $otherLan->id, 'published' => true, 'start' => now(), 'end' => now()->addHour()]);

        $testResponse = $this->getJson(route('api.v1.lans.events.show', [$lan, $event]));

        $testResponse->assertNotFound();
    }

    public function test_show_returns_404_when_event_is_unpublished(): void
    {
        $lan = Lan::factory()->create(['published' => true]);
        $event = Event::factory()->create(['lan_id' => $lan->id, 'published' => false, 'start' => now(), 'end' => now()->addHour()]);

        $testResponse = $this->getJson(route('api.v1.lans.events.show', [$lan, $event]));

        $testResponse->assertNotFound();
    }

    public function test_show_returns_404_when_lan_is_unpublished(): void
    {
        $lan = Lan::factory()->create(['published' => false]);
        $event = Event::factory()->create(['lan_id' => $lan->id, 'published' => true, 'start' => now(), 'end' => now()->addHour()]);

        $testResponse = $this->getJson(route('api.v1.lans.events.show', [$lan, $event]));

        $testResponse->assertNotFound();
    }

    public function test_flat_events_path_no_longer_resolves(): void
    {
        $testResponse = $this->getJson('/api/v1/events');

        $testResponse->assertNotFound();
    }
}
