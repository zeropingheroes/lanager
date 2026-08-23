<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Throwable;
use Zeropingheroes\Lanager\Exceptions\DiscordWebhookException;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessageImage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Requests\StoreEventDiscordNotificationMessageRequest;
use Zeropingheroes\Lanager\Services\DiscordWebhookService;
use Zeropingheroes\Lanager\Services\LocalImageService;

class EventDiscordNotificationMessageController extends Controller
{
    /**
     * Show the form for creating a notification message for the event.
     *
     * @throws AuthorizationException
     */
    public function create(Lan $lan, Event $event): ViewContract
    {
        $this->authorize('update', EventDiscordNotificationMessage::class);

        if ($event->lan_id !== $lan->id) {
            abort(404);
        }

        $lan->loadMissing('discordChannelWebhooks');

        return View::make('pages.event-discord-notification-messages.create')
            ->with('lan', $lan)
            ->with('event', $event)
            ->with('availableImages', (new LocalImageService)->all(extensions: DiscordWebhookService::PERMITTED_IMAGE_EXTENSIONS))
            ->with('selectedImages', [])
            ->with('maxImages', DiscordWebhookService::MAX_IMAGES)
            ->with('maxFileBytes', DiscordWebhookService::MAX_FILE_BYTES)
            ->with('maxTotalBytes', DiscordWebhookService::MAX_TOTAL_BYTES);
    }

    /**
     * Store a newly created notification message for the event.
     *
     * @throws AuthorizationException|Throwable
     */
    public function store(Request $httpRequest, Lan $lan, Event $event): RedirectResponse
    {
        $this->authorize('update', EventDiscordNotificationMessage::class);

        if ($event->lan_id !== $lan->id) {
            abort(404);
        }

        $input = [
            'message' => $httpRequest->input('message'),
            'image_paths' => $httpRequest->input('image_paths') ?? [],
        ];

        $storeRequest = new StoreEventDiscordNotificationMessageRequest($input);

        if ($storeRequest->invalid()) {
            Session::flash('error', $storeRequest->errors());

            return redirect()->back()->withInput();
        }

        DB::transaction(function () use ($event, $httpRequest, $input): void {
            $notification = EventDiscordNotificationMessage::create([
                'event_id' => $event->id,
                'message' => $input['message'],
                'automatic' => $httpRequest->has('automatic'),
            ]);

            foreach ($input['image_paths'] as $index => $imagePath) {
                EventDiscordNotificationMessageImage::create([
                    'event_discord_notification_message_id' => $notification->id,
                    'image_path' => $imagePath,
                    'sort_order' => $index,
                ]);
            }
        });

        Session::flash('success', trans('phrase.item-created-successfully', ['item' => trans('title.event-discord-notification-message')]));

        return redirect()->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Show the form for editing the notification message for the event.
     *
     * @throws AuthorizationException
     */
    public function edit(Lan $lan, Event $event): ViewContract
    {
        $this->authorize('update', EventDiscordNotificationMessage::class);

        if ($event->lan_id !== $lan->id) {
            abort(404);
        }

        $event->loadMissing('discordNotificationMessage.images');
        $lan->loadMissing('discordChannelWebhooks');

        $availableImages = (new LocalImageService)->all(extensions: DiscordWebhookService::PERMITTED_IMAGE_EXTENSIONS);

        $selectedImages = $event->discordNotificationMessage !== null
            ? (new LocalImageService)->fromPaths($event->discordNotificationMessage->images->pluck('image_path')->all())
            : [];

        return View::make('pages.event-discord-notification-messages.edit')
            ->with('lan', $lan)
            ->with('event', $event)
            ->with('availableImages', $availableImages)
            ->with('selectedImages', $selectedImages)
            ->with('maxImages', DiscordWebhookService::MAX_IMAGES)
            ->with('maxFileBytes', DiscordWebhookService::MAX_FILE_BYTES)
            ->with('maxTotalBytes', DiscordWebhookService::MAX_TOTAL_BYTES);
    }

    /**
     * Update the notification message for the event.
     *
     * @throws AuthorizationException|Throwable
     */
    public function update(Request $httpRequest, Lan $lan, Event $event): RedirectResponse
    {
        $this->authorize('update', EventDiscordNotificationMessage::class);

        if ($event->lan_id !== $lan->id) {
            abort(404);
        }

        $input = [
            'message' => $httpRequest->input('message'),
            'image_paths' => $httpRequest->input('image_paths') ?? [],
        ];

        $storeRequest = new StoreEventDiscordNotificationMessageRequest($input);

        if ($storeRequest->invalid()) {
            Session::flash('error', $storeRequest->errors());

            return redirect()->back()->withInput();
        }

        $event->loadMissing('discordNotificationMessage');

        DB::transaction(function () use ($event, $httpRequest, $input): void {
            $event->discordNotificationMessage->update([
                'message' => $input['message'],
                'automatic' => $httpRequest->has('automatic'),
            ]);

            $event->discordNotificationMessage->images()->delete();

            foreach ($input['image_paths'] as $index => $imagePath) {
                EventDiscordNotificationMessageImage::create([
                    'event_discord_notification_message_id' => $event->discordNotificationMessage->id,
                    'image_path' => $imagePath,
                    'sort_order' => $index,
                ]);
            }
        });

        Session::flash('success', trans('phrase.item-updated-successfully', ['item' => trans('title.event-discord-notification-message')]));

        return redirect()->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Delete the notification message for the event.
     *
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, Event $event): RedirectResponse
    {
        $this->authorize('update', EventDiscordNotificationMessage::class);

        if ($event->lan_id !== $lan->id) {
            abort(404);
        }

        $event->loadMissing('discordNotificationMessage');

        $event->discordNotificationMessage->delete();

        Session::flash('success', trans('phrase.item-name-deleted', ['item' => trans('title.event-discord-notification-message'), 'name' => $event->name]));

        return redirect()->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Manually send an event's saved Discord notification message to the LAN's live webhook.
     *
     * @throws AuthorizationException
     */
    public function send(Request $httpRequest, Lan $lan, Event $event): RedirectResponse
    {
        $this->authorize('send', EventDiscordNotificationMessage::class);

        if ($event->lan_id !== $lan->id) {
            abort(404);
        }

        $event->loadMissing('discordNotificationMessage.images');

        $notification = $event->discordNotificationMessage;

        if ($notification === null) {
            Session::flash('error', [trans('phrase.no-event-discord-notification-message-configured')]);

            return redirect()->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
        }

        $lan->loadMissing('discordChannelWebhooks');

        $liveWebhook = $lan->discordChannelWebhooks->firstWhere('purpose', 'live');

        if ($liveWebhook === null) {
            Session::flash('error', [trans('phrase.no-live-webhook-configured')]);

            return redirect()->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
        }

        $imagePaths = $notification->images->pluck('image_path')->all();

        $discordWebhookService = new DiscordWebhookService;

        try {
            $discordWebhookService->send(
                $liveWebhook->webhook_url,
                $discordWebhookService->resolvePlaceholders($notification->message, $event->placeholders()),
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

            Session::flash('error', [trans('phrase.failed-to-send-discord-message')]);

            return redirect()->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
        }

        $notification->update(['automatic' => false]);

        Log::info('Manual event Discord notification sent', [
            'event_id' => $event->id,
            'lan_id' => $lan->id,
            'user_id' => $httpRequest->user()?->id,
            'purpose' => 'live',
            'result' => 'success',
        ]);

        Session::flash('success', trans('phrase.discord-message-sent-successfully', ['purpose' => trans('title.live')]));

        return redirect()->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Preview message content from the "create" and "edit forms in Discord.
     *
     * @throws AuthorizationException
     */
    public function preview(Request $httpRequest, Lan $lan, Event $event): JsonResponse
    {
        $this->authorize('preview', EventDiscordNotificationMessage::class);

        if ($event->lan_id !== $lan->id) {
            abort(404);
        }

        $input = [
            'message' => $httpRequest->input('message'),
        ];

        $previewRequest = new StoreEventDiscordNotificationMessageRequest($input);

        if ($previewRequest->invalid()) {
            return response()->json(['errors' => $previewRequest->errors()], 422);
        }

        $lan->loadMissing('discordChannelWebhooks');

        $testWebhook = $lan->discordChannelWebhooks->firstWhere('purpose', 'test');

        if ($testWebhook === null) {
            return response()->json(['errors' => [trans('phrase.no-test-webhook-configured')]], 422);
        }

        $discordWebhookService = new DiscordWebhookService;

        try {
            $discordWebhookService->send(
                $testWebhook->webhook_url,
                $discordWebhookService->resolvePlaceholders($input['message'], $event->placeholders()),
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
