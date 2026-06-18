<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Requests\StoreDiscordChannelWebhookRequest;

class DiscordChannelWebhookController extends Controller
{
    /**
     * Display all configured webhooks for a LAN, with an inline creation form.
     *
     * @throws AuthorizationException
     */
    public function index(Request $request, Lan $lan): ViewContract
    {
        $this->authorize('index', DiscordChannelWebhook::class);

        $webhooks = $lan->discordChannelWebhooks()->orderBy('purpose')->get();

        $configuredPurposes = $webhooks->pluck('purpose')->all();
        $availablePurposes = array_values(array_diff(['live', 'test'], $configuredPurposes));

        return View::make('pages.discord-channel-webhooks.index')
            ->with('lan', $lan)
            ->with('webhooks', $webhooks)
            ->with('availablePurposes', $availablePurposes);
    }

    /**
     * Store a newly created webhook for a LAN.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest, Lan $lan): RedirectResponse
    {
        $this->authorize('create', DiscordChannelWebhook::class);

        $input = [
            'lan_id' => $lan->id,
            'purpose' => $httpRequest->input('purpose'),
            'webhook_url' => $httpRequest->input('webhook_url'),
        ];

        $storeDiscordChannelWebhookRequest = new StoreDiscordChannelWebhookRequest($input);

        if ($storeDiscordChannelWebhookRequest->invalid()) {
            Session::flash('error', $storeDiscordChannelWebhookRequest->errors());

            return redirect()->back()->withInput();
        }

        DiscordChannelWebhook::create($input);

        Session::flash(
            'success',
            trans('phrase.item-created-successfully', ['item' => trans('title.discord-channel-webhook')])
        );

        return redirect()->route('lans.discord-channel-webhooks.index', ['lan' => $lan]);
    }

    /**
     * Delete a webhook belonging to a LAN.
     *
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Lan $lan, DiscordChannelWebhook $discordChannelWebhook): RedirectResponse
    {
        $this->authorize('delete', $discordChannelWebhook);

        if ($discordChannelWebhook->lan_id !== $lan->id) {
            abort(404);
        }

        $discordChannelWebhook->delete();

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', [
                'item' => trans('title.discord-channel-webhook'),
                'name' => $discordChannelWebhook->purpose,
            ])
        );

        return redirect()->route('lans.discord-channel-webhooks.index', ['lan' => $lan]);
    }
}
