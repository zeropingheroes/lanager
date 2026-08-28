<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessageImage;
use Zeropingheroes\Lanager\Models\Lan;

class SendDiscordEventNotificationMessagesTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private const string COMMAND = 'lanager:send-discord-event-notification-messages';

    private const string LIVE_WEBHOOK_URL = 'https://discord.com/api/webhooks/123456789012345678/abcdefghijklmnopqrstuvwxyz_ABCDEF-token';

    private Lan $lan;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-06-22 12:00:00');

        $this->lan = Lan::factory()->create([
            'start' => '2026-06-22 00:00:00',
            'end' => '2026-06-23 00:00:00',
        ]);

        Log::spy();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    private function createDueEvent(array $eventOverrides = [], array $notificationOverrides = []): Event
    {
        $event = Event::factory()->create(array_merge([
            'lan_id' => $this->lan->id,
            'start' => now()->subSeconds(30),
            'end' => now()->addHour(),
            'published' => true,
        ], $eventOverrides));

        EventDiscordNotificationMessage::factory()->create(array_merge([
            'event_id' => $event->id,
        ], $notificationOverrides));

        return $event->fresh();
    }

    public function test_due_event_is_sent_and_automatically_sent_at_is_set(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::LIVE_WEBHOOK_URL,
        ]);

        $event = $this->createDueEvent();

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL);
        $this->assertNotNull($event->discordNotificationMessage->fresh()->automatically_sent_at);
        $this->assertTrue(now()->diffInSeconds($event->discordNotificationMessage->fresh()->automatically_sent_at) < 5);

        Log::shouldHaveReceived('info')->withArgs(
            fn ($message, $context = []) => $message === trans('phrase.discord-event-notification-message-sent')
                && $context['event_id'] === $event->id
                && $context['result'] === 'success'
        )->once();
    }

    public function test_substitutes_placeholders_and_leaves_stored_message_raw(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::LIVE_WEBHOOK_URL,
        ]);

        $event = $this->createDueEvent(notificationOverrides: [
            'message' => 'New event: {{event.name}} - {{event.url}}',
        ]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        $expectedContent = sprintf(
            'New event: %s - %s',
            $event->name,
            route('lans.events.show', ['lan' => $this->lan, 'event' => $event])
        );
        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
        $this->assertSame('New event: {{event.name}} - {{event.url}}', $event->discordNotificationMessage->fresh()->message);
    }

    public function test_due_event_with_blank_message_uses_default_message(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::LIVE_WEBHOOK_URL,
        ]);

        $event = $this->createDueEvent(notificationOverrides: ['message' => null]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        $expectedContent = str_replace(
            ['{{event.name}}', '{{event.url}}'],
            [$event->name, route('lans.events.show', ['lan' => $this->lan, 'event' => $event])],
            trans('phrase.default-event-discord-notification-message')
        );
        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
    }

    public function test_due_event_with_blank_message_uses_lan_default_message_when_lan_has_one(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::LIVE_WEBHOOK_URL,
        ]);
        $this->lan->update(['default_event_discord_notification_message' => 'LAN default: {{event.name}} - {{event.url}}']);

        $event = $this->createDueEvent(notificationOverrides: ['message' => null]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        $expectedContent = sprintf(
            'LAN default: %s - %s',
            $event->name,
            route('lans.events.show', ['lan' => $this->lan, 'event' => $event])
        );
        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL && $request->data()['content'] === $expectedContent);
    }

    public function test_event_with_automatic_false_is_skipped(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $this->createDueEvent(notificationOverrides: ['automatic' => false]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertNothingSent();
    }

    public function test_event_with_no_notification_record_is_skipped(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        Event::factory()->create([
            'lan_id' => $this->lan->id,
            'start' => now()->subSeconds(30),
            'end' => now()->addHour(),
            'published' => true,
        ]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertNothingSent();
    }

    public function test_unpublished_event_is_skipped(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $this->createDueEvent(['published' => false]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertNothingSent();
    }

    public function test_event_is_skipped_when_lan_has_no_live_webhook(): void
    {
        Http::fake();

        $this->createDueEvent();

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertNothingSent();
    }

    public function test_event_outside_time_window_is_skipped(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $this->createDueEvent(['start' => now()->subMinutes(5), 'end' => now()->addHour()]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertNothingSent();
    }

    public function test_already_sent_event_is_not_resent(): void
    {
        Http::fake();
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $event = $this->createDueEvent();
        $event->discordNotificationMessage->update(['automatically_sent_at' => $event->start->copy()->addSeconds(1)]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertNothingSent();
    }

    public function test_rescheduling_to_a_later_time_makes_event_eligible_again(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $event = $this->createDueEvent();
        $originalStart = $event->start;

        // The notification was already sent for the event's original start time, making it
        // ineligible (automatically_sent_at >= start)...
        $event->discordNotificationMessage->update(['automatically_sent_at' => $originalStart->copy()->addSecond()]);

        $this->artisan(self::COMMAND)->assertExitCode(0);
        Http::assertNothingSent();

        // ...but the event has since been rescheduled to a time later than that automatically_sent_at,
        // making automatically_sent_at < start true again.
        $event->update(['start' => now()->subSeconds(10)]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertSent(fn ($request) => $request->url() === self::LIVE_WEBHOOK_URL);
    }

    public function test_failed_dispatch_leaves_automatically_sent_at_unchanged_and_logs_error(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(['message' => 'Unknown Webhook'], 404)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $event = $this->createDueEvent();

        $this->artisan(self::COMMAND)->assertExitCode(0);

        $this->assertNull($event->discordNotificationMessage->fresh()->automatically_sent_at);

        Log::shouldHaveReceived('error')->withArgs(
            fn ($message, $context) => $message === trans('phrase.failed-to-send-discord-event-notification-message')
                && $context['event_id'] === $event->id
                && $context['http_status'] === 404
        )->once();
    }

    public function test_one_events_exception_does_not_halt_the_run(): void
    {
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $failingEvent = $this->createDueEvent();
        $succeedingEvent = $this->createDueEvent();

        Http::fake(function ($request) use ($failingEvent) {
            if ($request->url() === self::LIVE_WEBHOOK_URL && str_contains((string) $request->body(), (string) $failingEvent->discordNotificationMessage->message)) {
                throw new \RuntimeException('Unexpected connection-level failure');
            }

            return Http::response(null, 204);
        });

        $this->artisan(self::COMMAND)->assertExitCode(0);

        $this->assertNull($failingEvent->discordNotificationMessage->fresh()->automatically_sent_at);
        $this->assertNotNull($succeedingEvent->discordNotificationMessage->fresh()->automatically_sent_at);

        Log::shouldHaveReceived('error')->withArgs(
            fn ($message, $context) => $message === trans('phrase.unexpected-error-sending-discord-event-notification-message')
                && $context['event_id'] === $failingEvent->id
        )->once();
    }

    public function test_command_passes_image_paths_to_service_as_multipart(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/first.png', 'png-data');
        Storage::disk('public')->put('images/second.png', 'png-data-2');

        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 200)]);
        DiscordChannelWebhook::factory()->live()->create([
            'lan_id' => $this->lan->id,
            'webhook_url' => self::LIVE_WEBHOOK_URL,
        ]);

        $event = $this->createDueEvent();
        EventDiscordNotificationMessageImage::create([
            'event_discord_notification_message_id' => $event->discordNotificationMessage->id,
            'image_path' => 'images/first.png',
            'sort_order' => 0,
        ]);
        EventDiscordNotificationMessageImage::create([
            'event_discord_notification_message_id' => $event->discordNotificationMessage->id,
            'image_path' => 'images/second.png',
            'sort_order' => 1,
        ]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Http::assertSent(function ($request): bool {
            $contentType = $request->header('Content-Type')[0] ?? '';
            $body = $request->body();

            $firstPos = strpos($body, 'first.png');
            $secondPos = strpos($body, 'second.png');

            return $request->url() === self::LIVE_WEBHOOK_URL
                && str_contains($contentType, 'multipart/form-data')
                && $firstPos !== false
                && $secondPos !== false
                && $firstPos < $secondPos;
        });
    }

    public function test_run_summary_is_logged(): void
    {
        Http::fake([self::LIVE_WEBHOOK_URL => Http::response(null, 204)]);
        DiscordChannelWebhook::factory()->live()->create(['lan_id' => $this->lan->id, 'webhook_url' => self::LIVE_WEBHOOK_URL]);

        $this->createDueEvent();

        // This event passes the eligibility query (it doesn't filter on automatically_sent_at) but
        // is skipped by the command's own belt-and-braces re-check, incrementing events_skipped.
        $alreadySentEvent = $this->createDueEvent();
        $alreadySentEvent->discordNotificationMessage->update(['automatically_sent_at' => $alreadySentEvent->start->copy()->addSeconds(1)]);

        $this->artisan(self::COMMAND)->assertExitCode(0);

        Log::shouldHaveReceived('info')->withArgs(
            fn ($message) => $message === trans('phrase.discord-event-notification-messages-run-completed', [
                'processed' => 1,
                'skipped' => 1,
                'failed' => 0,
            ])
        )->once();
    }
}
