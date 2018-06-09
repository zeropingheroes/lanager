<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreEventTypeRequest extends Request
{
    use LaravelValidation;

    /**
     * Whether the request is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        $this->validationRules = [
            'name'   => ['required', 'max:255'],
            'colour' => ['required', 'max:7'], // TODO: validate hex colour
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}