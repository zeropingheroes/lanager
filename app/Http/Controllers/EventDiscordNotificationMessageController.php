<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Throwable;
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
            'message' => $this->discardIfDefault($httpRequest->input('message'), $lan),
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
            'message' => $this->discardIfDefault($httpRequest->input('message'), $lan),
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
     * The submitted message, or null if it is blank or matches the default message
     * (the LAN's default message, if it has one, otherwise the system default message).
     */
    private function discardIfDefault(?string $message, Lan $lan): ?string
    {
        if (! $message) {
            return null;
        }

        $default = $lan->default_event_discord_notification_message
            ?? trans('phrase.default-event-discord-notification-message');

        return $message === $default ? null : $message;
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
}
