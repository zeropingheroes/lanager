<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Zeropingheroes\Lanager\Exceptions\DiscordWebhookException;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Models\DiscordChannelWebhook;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Requests\SendDiscordChannelWebhookMessageRequest;
use Zeropingheroes\Lanager\Services\DiscordWebhookService;

class DiscordChannelWebhookMessageController extends Controller
{
    /**
     * Send a message to a Discord webhook.
     *
     * @throws AuthorizationException
     */
    public function send(Request $httpRequest, Lan $lan, DiscordChannelWebhook $discordChannelWebhook): JsonResponse
    {
        $this->authorize('discord-webhook-messages.store');

        if ($discordChannelWebhook->lan_id !== $lan->id) {
            abort(404);
        }

        $input = [
            'message' => $httpRequest->input('message'),
        ];

        $sendDiscordChannelWebhookMessageRequest = new SendDiscordChannelWebhookMessageRequest($input);

        if ($sendDiscordChannelWebhookMessageRequest->invalid()) {
            return response()->json(['errors' => $sendDiscordChannelWebhookMessageRequest->errors()], 422);
        }

        try {
            (new DiscordWebhookService)->send($discordChannelWebhook->webhook_url, $input['message']);
        } catch (DiscordWebhookException $discordWebhookException) {
            Log::error('Manual Discord message failed', [
                'lan_id' => $lan->id,
                'user_id' => $httpRequest->user()?->id,
                'purpose' => $discordChannelWebhook->purpose,
                'http_status' => $discordWebhookException->httpStatus,
                'error' => $discordWebhookException->errorBody,
            ]);

            return response()->json(['errors' => [trans('phrase.failed-to-send-discord-message')]], 502);
        }

        Log::info('Manual Discord message sent', [
            'lan_id' => $lan->id,
            'user_id' => $httpRequest->user()?->id,
            'purpose' => $discordChannelWebhook->purpose,
            'result' => 'success',
        ]);

        return response()->json(['message' => trans('phrase.discord-message-sent-successfully', ['purpose' => trans('title.'.$discordChannelWebhook->purpose)])]);
    }
}
