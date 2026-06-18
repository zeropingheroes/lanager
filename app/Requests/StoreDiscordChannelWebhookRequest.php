<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Validation\Rule;

class StoreDiscordChannelWebhookRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'lan_id' => ['required', 'integer', 'exists:lans,id'],
            'purpose' => [
                'required',
                'string',
                Rule::in(['live', 'test']),
                Rule::unique('discord_channel_webhooks')->where(
                    fn ($query) => $query->where('lan_id', $this->input['lan_id'])
                ),
            ],
            'webhook_url' => [
                'required',
                'url',
                'max:2048',
                'regex:/^https:\/\/(discord\.com|discordapp\.com)\/api\/webhooks\/\d+\/[\w-]+$/',
                Rule::unique('discord_channel_webhooks', 'webhook_url')->where(
                    fn ($query) => $query->where('lan_id', $this->input['lan_id'])
                ),
            ],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
