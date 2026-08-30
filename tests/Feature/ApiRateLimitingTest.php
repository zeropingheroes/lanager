<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ApiRateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_requests_within_the_limit_are_not_throttled(): void
    {
        for ($i = 1; $i <= 60; $i++) {
            $this->getJson(route('api.lans.index'))->assertStatus(200);
        }
    }

    public function test_requests_beyond_the_limit_receive_a_429_response(): void
    {
        for ($i = 1; $i <= 60; $i++) {
            $this->getJson(route('api.lans.index'));
        }

        $this->getJson(route('api.lans.index'))->assertStatus(429);
    }

    public function test_web_routes_are_not_subject_to_the_api_rate_limit(): void
    {
        for ($i = 1; $i <= 65; $i++) {
            $this->assertNotSame(429, $this->get(route('home'))->getStatusCode());
        }
    }
}
