<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Exceptions\DiscordWebhookException;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Requests\StoreEventDiscordNotificationMessageRequest;
use Zeropingheroes\Lanager\Services\DiscordWebhookService;

class EventDiscordNotificationMessageController extends Controller
{
    /**
     * Manually send an event's saved Discord notification message to the LAN's live webhook.
     *
     * @throws AuthorizationException
     */
    public function send(Request $httpRequest, Event $event): JsonResponse
    {
        $this->authorize('send', EventDiscordNotificationMessage::class);

        $event->loadMissing('discordNotificationMessage.images', 'lan.discordChannelWebhooks');

        $notification = $event->discordNotificationMessage;

        if ($notification === null) {
            return response()->json(['errors' => [trans('phrase.no-event-discord-notification-message-configured')]], 422);
        }

        $lan = $event->lan;

        $liveWebhook = $lan->discordChannelWebhooks->firstWhere('purpose', 'live');

        if ($liveWebhook === null) {
            return response()->json(['errors' => [trans('phrase.no-live-webhook-configured')]], 422);
        }

        $imagePaths = $notification->images->pluck('image_path')->all();

        $discordWebhookService = new DiscordWebhookService;

        try {
            $discordWebhookService->send(
                $liveWebhook->webhook_url,
                $discordWebhookService->resolvePlaceholders(
                    $notification->content(),
                    $event->placeholders()
                ),
                $imagePaths
            );
        } catch (DiscordWebhookException|ConnectionException $exception) {
            Log::error('Manual event Discord notification failed', [
                'event_id' => $event->id,
                'lan_id' => $lan->id,
                'user_id' => $httpRequest->user()?->id,
                'purpose' => 'live',
                'http_status' => $exception instanceof DiscordWebhookException ? $exception->httpStatus : null,
                'error' => $exception instanceof DiscordWebhookException ? $exception->errorBody : $exception->getMessage(),
            ]);

            return response()->json(['errors' => [trans('phrase.failed-to-send-discord-message')]], 502);
        }

        $notification->update(['automatic' => false]);

        Log::info('Manual event Discord notification sent', [
            'event_id' => $event->id,
            'lan_id' => $lan->id,
            'user_id' => $httpRequest->user()?->id,
            'purpose' => 'live',
            'result' => 'success',
        ]);

        return response()->json(['message' => trans('phrase.discord-message-sent-successfully', ['purpose' => trans('title.live')])]);
    }

    /**
     * Preview message content from the "create" and "edit" forms in Discord.
     *
     * @throws AuthorizationException
     */
    public function preview(Request $httpRequest, Event $event): JsonResponse
    {
        $this->authorize('preview', EventDiscordNotificationMessage::class);

        $input = [
            'message' => $httpRequest->filled('message') ? $httpRequest->input('message') : null,
        ];

        $storeEventDiscordNotificationMessageRequest = new StoreEventDiscordNotificationMessageRequest($input);

        if ($storeEventDiscordNotificationMessageRequest->invalid()) {
            return response()->json(['errors' => $storeEventDiscordNotificationMessageRequest->errors()], 422);
        }

        $event->loadMissing('lan.discordChannelWebhooks');

        $lan = $event->lan;

        $testWebhook = $lan->discordChannelWebhooks->firstWhere('purpose', 'test');

        if ($testWebhook === null) {
            return response()->json(['errors' => [trans('phrase.no-test-webhook-configured')]], 422);
        }

        $discordWebhookService = new DiscordWebhookService;

        $message = $input['message']
            ?? $lan->default_event_discord_notification_message
            ?? trans('phrase.default-event-discord-notification-message');

        try {
            $discordWebhookService->send(
                $testWebhook->webhook_url,
                $discordWebhookService->resolvePlaceholders($message, $event->placeholders()),
                (array) ($httpRequest->input('image_paths') ?? [])
            );
        } catch (DiscordWebhookException|ConnectionException $exception) {
            Log::error('Event Discord notification preview failed', [
                'lan_id' => $lan->id,
                'user_id' => $httpRequest->user()?->id,
                'purpose' => 'test',
                'http_status' => $exception instanceof DiscordWebhookException ? $exception->httpStatus : null,
                'error' => $exception instanceof DiscordWebhookException ? $exception->errorBody : $exception->getMessage(),
            ]);

            return response()->json(['errors' => [trans('phrase.event-discord-notification-preview-failed')]], 502);
        }

        Log::info('Event Discord notification preview sent', [
            'lan_id' => $lan->id,
            'user_id' => $httpRequest->user()?->id,
            'purpose' => 'test',
            'result' => 'success',
        ]);

        return response()->json(['message' => trans('phrase.event-discord-notification-preview-sent')]);
    }
}
