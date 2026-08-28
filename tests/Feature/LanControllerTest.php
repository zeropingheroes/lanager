<?php

namespace Tests\Feature;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Role;
use Zeropingheroes\Lanager\Models\User;

class LanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->adminUser = User::factory()->create();
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $this->adminUser->roles()->attach($adminRole->id, ['assigned_by' => $this->adminUser->id]);
    }

    private function validLanInput(array $overrides = []): array
    {
        return array_merge([
            'name' => 'My Great LAN',
            'start' => '2026-06-01 18:00',
            'end' => '2026-06-03 18:00',
        ], $overrides);
    }

    // --- validation (3.1) ---

    public function test_store_accepts_a_blank_default_event_discord_notification_message(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.store'), $this->validLanInput());

        $testResponse->assertRedirect();
        $this->assertDatabaseHas('lans', [
            'name' => 'My Great LAN',
            'default_event_discord_notification_message' => null,
        ]);
    }

    public function test_store_rejects_a_default_event_discord_notification_message_over_2000_characters(): void
    {
        $testResponse = $this->actingAs($this->adminUser)
            ->post(route('lans.store'), $this->validLanInput([
                'default_event_discord_notification_message' => str_repeat('a', 2001),
            ]));

        $testResponse->assertRedirect();
        $this->assertDatabaseMissing('lans', ['name' => 'My Great LAN']);
    }

    // --- store()/update() persistence (3.2) ---

    public function test_store_persists_a_submitted_default_event_discord_notification_message(): void
    {
        $this->actingAs($this->adminUser)
            ->post(route('lans.store'), $this->validLanInput([
                'default_event_discord_notification_message' => 'Custom LAN default message',
            ]));

        $this->assertDatabaseHas('lans', [
            'name' => 'My Great LAN',
            'default_event_discord_notification_message' => 'Custom LAN default message',
        ]);
    }

    public function test_update_persists_a_submitted_default_event_discord_notification_message(): void
    {
        $lan = Lan::factory()->create();

        $this->actingAs($this->adminUser)
            ->put(route('lans.update', ['lan' => $lan]), $this->validLanInput([
                'default_event_discord_notification_message' => 'Updated LAN default message',
            ]));

        $this->assertDatabaseHas('lans', [
            'id' => $lan->id,
            'default_event_discord_notification_message' => 'Updated LAN default message',
        ]);
    }

    public function test_update_clears_default_event_discord_notification_message_when_left_blank(): void
    {
        $lan = Lan::factory()->create(['default_event_discord_notification_message' => 'Old default message']);

        $this->actingAs($this->adminUser)
            ->put(route('lans.update', ['lan' => $lan]), $this->validLanInput());

        $this->assertDatabaseHas('lans', [
            'id' => $lan->id,
            'default_event_discord_notification_message' => null,
        ]);
    }

    public function test_store_discards_message_matching_the_system_default_and_stores_null(): void
    {
        $this->actingAs($this->adminUser)
            ->post(route('lans.store'), $this->validLanInput([
                'default_event_discord_notification_message' => trans('phrase.default-event-discord-notification-message'),
            ]));

        $this->assertDatabaseHas('lans', [
            'name' => 'My Great LAN',
            'default_event_discord_notification_message' => null,
        ]);
    }

    public function test_update_discards_message_matching_the_system_default_and_stores_null(): void
    {
        $lan = Lan::factory()->create(['default_event_discord_notification_message' => 'Old custom message']);

        $this->actingAs($this->adminUser)
            ->put(route('lans.update', ['lan' => $lan]), $this->validLanInput([
                'default_event_discord_notification_message' => trans('phrase.default-event-discord-notification-message'),
            ]));

        $this->assertDatabaseHas('lans', [
            'id' => $lan->id,
            'default_event_discord_notification_message' => null,
        ]);
    }

    // --- create()/edit() views (3.3) ---

    public function test_create_page_shows_default_event_discord_notification_message_field(): void
    {
        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.create'));

        $testResponse->assertOk();
        $testResponse->assertSee('name="default_event_discord_notification_message"', false);
        $testResponse->assertSee(trans('title.default-event-discord-notification-message'));
        $testResponse->assertSee(trans('phrase.default-event-discord-notification-message-help'), false);
        $testResponse->assertSee(trans('phrase.default-event-discord-notification-message'), false);
    }

    public function test_edit_page_prefills_existing_default_event_discord_notification_message(): void
    {
        $lan = Lan::factory()->create(['default_event_discord_notification_message' => 'Existing LAN default message']);

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.edit', ['lan' => $lan]));

        $testResponse->assertOk();
        $testResponse->assertSee('Existing LAN default message', false);
    }

    public function test_edit_page_shows_discord_markdown_help_text(): void
    {
        $lan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.edit', ['lan' => $lan]));

        $testResponse->assertOk();
        $testResponse->assertSee(
            'href="'.trans('phrase.discord-markdown-help-link-url').'"',
            false
        );
    }

    public function test_create_page_prefills_message_field_with_system_default_when_lan_has_no_value(): void
    {
        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.create'));

        $testResponse->assertOk();
        $this->assertSame(
            2,
            substr_count($testResponse->getContent(), trans('phrase.default-event-discord-notification-message')),
            'Expected the default message to appear as both the placeholder and the pre-filled textarea value.'
        );
    }

    public function test_edit_page_prefills_message_field_with_system_default_when_lan_has_no_value(): void
    {
        $lan = Lan::factory()->create();

        $testResponse = $this->actingAs($this->adminUser)->get(route('lans.edit', ['lan' => $lan]));

        $testResponse->assertOk();
        $this->assertSame(
            2,
            substr_count($testResponse->getContent(), trans('phrase.default-event-discord-notification-message')),
            'Expected the default message to appear as both the placeholder and the pre-filled textarea value.'
        );
    }
}
