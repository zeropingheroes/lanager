<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Exceptions\DiscordWebhookException;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Requests\StoreDiscordChannelWebhookMessageRequest;
use Zeropingheroes\Lanager\Services\DiscordWebhookService;

class DiscordChannelWebhookMessageController extends Controller
{
    /**
     * Show the message compose form for a specific webhook.
     *
     * @throws AuthorizationException
     */
    public function create(Request $request, Lan $lan, DiscordChannelWebhook $discordChannelWebhook): ViewContract
    {
        $this->authorize('discord-webhook-messages.create');

        if ($discordChannelWebhook->lan_id !== $lan->id) {
            abort(404);
        }

        return View::make('pages.discord-channel-webhook-messages.create')
            ->with('lan', $lan)
            ->with('webhook', $discordChannelWebhook);
    }

    /**
     * Send a message to a Discord webhook.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest, Lan $lan, DiscordChannelWebhook $discordChannelWebhook): RedirectResponse
    {
        $this->authorize('discord-webhook-messages.store');

        if ($discordChannelWebhook->lan_id !== $lan->id) {
            abort(404);
        }

        $input = [
            'content' => $httpRequest->input('content'),
        ];

        $storeDiscordChannelWebhookMessageRequest = new StoreDiscordChannelWebhookMessageRequest($input);

        if ($storeDiscordChannelWebhookMessageRequest->invalid()) {
            Session::flash('error', $storeDiscordChannelWebhookMessageRequest->errors());

            return redirect()->back()->withInput();
        }

        try {
            (new DiscordWebhookService)->send($discordChannelWebhook->webhook_url, $input['content']);
        } catch (DiscordWebhookException $discordWebhookException) {
            Log::error('Manual Discord message failed', [
                'lan_id' => $lan->id,
                'user_id' => $httpRequest->user()?->id,
                'purpose' => $discordChannelWebhook->purpose,
                'http_status' => $discordWebhookException->httpStatus,
                'error' => $discordWebhookException->errorBody,
            ]);

            Session::flash('error', [trans('phrase.failed-to-send-discord-message')]);

            return redirect()->back();
        }

        Log::info('Manual Discord message sent', [
            'lan_id' => $lan->id,
            'user_id' => $httpRequest->user()?->id,
            'purpose' => $discordChannelWebhook->purpose,
            'result' => 'success',
        ]);

        Session::flash('success', trans('phrase.discord-message-sent-successfully', ['purpose' => trans('title.'.$discordChannelWebhook->purpose)]));

        return redirect()->route('lans.discord-channel-webhooks.index', ['lan' => $lan]);
    }
}
