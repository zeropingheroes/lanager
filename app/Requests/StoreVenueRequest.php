<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreVenueRequest extends Request
{
    use LaravelValidation;

    /**
     * Whether the request is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        $this->validationRules = [
            'name'               => ['required', 'max:255'],
            'street_address'     => ['required', 'max:255'],
            'content'            => ['nullable'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
