<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

class StoreApiTokenRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'name' => ['required', 'max:255'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
