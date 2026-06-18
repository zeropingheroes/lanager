<?php

namespace Zeropingheroes\Lanager\Exceptions;

use RuntimeException;

class DiscordWebhookException extends RuntimeException
{
    public function __construct(
        public readonly int $httpStatus,
        public readonly string $errorBody,
    ) {
        parent::__construct('Discord webhook request failed with HTTP '.$httpStatus);
    }
}
