<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Lan;

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
}
