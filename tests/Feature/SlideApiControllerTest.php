<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Slide;

class SlideApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_show_includes_start_and_end_when_set(): void
    {
        $lan = Lan::factory()->create(['published' => true]);
        $start = now();
        $end = now()->addHour();
        $slide = Slide::create([
            'lan_id' => $lan->id,
            'name' => 'Test slide',
            'content' => 'Test content',
            'position' => 1,
            'duration' => 10,
            'published' => true,
            'start' => $start,
            'end' => $end,
        ]);

        $testResponse = $this->getJson(route('api.v1.lans.slides.show', [$lan, $slide]));

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.start', $start->toIso8601String());
        $testResponse->assertJsonPath('data.end', $end->toIso8601String());
    }

    public function test_show_includes_null_start_and_end_when_unset(): void
    {
        $lan = Lan::factory()->create(['published' => true]);
        $slide = Slide::create([
            'lan_id' => $lan->id,
            'name' => 'Test slide',
            'content' => 'Test content',
            'position' => 1,
            'duration' => 10,
            'published' => true,
            'start' => null,
            'end' => null,
        ]);

        $testResponse = $this->getJson(route('api.v1.lans.slides.show', [$lan, $slide]));

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.start', null);
        $testResponse->assertJsonPath('data.end', null);
    }

    public function test_show_includes_published_as_a_real_boolean(): void
    {
        $lan = Lan::factory()->create(['published' => true]);
        $slide = Slide::create([
            'lan_id' => $lan->id,
            'name' => 'Test slide',
            'content' => 'Test content',
            'position' => 1,
            'duration' => 10,
            'published' => true,
        ]);

        $testResponse = $this->getJson(route('api.v1.lans.slides.show', [$lan, $slide]));

        $testResponse->assertOk();
        $testResponse->assertJsonPath('data.published', true);
    }
}
