<?php

namespace Zeropingheroes\Lanager\Requests;

class SendDiscordChannelWebhookMessageRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'message' => ['required', 'string', 'max:2000'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
