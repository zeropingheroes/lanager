<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Venue;

class LanApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_show_omits_venue_when_not_requested(): void
    {
        $venue = Venue::factory()->create();
        $lan = Lan::factory()->create(['published' => true, 'venue_id' => $venue->id]);

        $testResponse = $this->getJson(route('api.v1.lans.show', $lan));

        $testResponse->assertOk();
        $testResponse->assertJsonMissingPath('data.venue');
    }

    public function test_show_includes_venue_when_requested(): void
    {
        $venue = Venue::factory()->create();
        $lan = Lan::factory()->create(['published' => true, 'venue_id' => $venue->id]);

        $testResponse = $this->getJson(route('api.v1.lans.show', $lan).'?venue');

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.venue.id', $venue->id);
        $testResponse->assertJsonPath('data.venue.name', $venue->name);
        $testResponse->assertJsonPath('data.venue.street_address', $venue->street_address);
        $testResponse->assertJsonPath('data.venue.description', $venue->description);
    }

    public function test_show_includes_null_venue_when_requested_but_lan_has_none(): void
    {
        $lan = Lan::factory()->create(['published' => true, 'venue_id' => null]);

        $testResponse = $this->getJson(route('api.v1.lans.show', $lan).'?venue');

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.venue', null);
    }

    public function test_show_always_includes_published(): void
    {
        $lan = Lan::factory()->create(['published' => true]);

        $testResponse = $this->getJson(route('api.v1.lans.show', $lan));

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.published', true);
    }

    public function test_index_always_includes_published(): void
    {
        $lan = Lan::factory()->create(['published' => true]);

        $testResponse = $this->getJson(route('api.v1.lans.index'));

        $testResponse->assertOk();
        $entry = collect($testResponse->json('data'))->firstWhere('id', $lan->id);
        $this->assertSame(true, $entry['published']);
    }
}
