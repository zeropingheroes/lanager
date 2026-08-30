<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tests\TestCase;

class ApiVersioningTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_versioned_path_resolves(): void
    {
        $this->getJson('/api/v1/lans')->assertOk();
    }

    public function test_unversioned_path_no_longer_resolves(): void
    {
        $this->getJson('/api/lans')->assertNotFound();
    }

    public function test_versioned_route_name_resolves_to_versioned_path(): void
    {
        $this->assertStringEndsWith('/api/v1/lans', route('api.v1.lans.index'));
    }

    public function test_unversioned_route_name_no_longer_exists(): void
    {
        $this->expectException(RouteNotFoundException::class);

        route('api.lans.index');
    }
}
