<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreEventTypeRequest extends Request
{
    use LaravelValidation;

    /**
     * Validation rules for the Laravel validator
     *
     * @var array
     */
    protected $laravelValidationRules = [
        'name' => ['required', 'max:255'],
        'colour' => ['required', 'max:7'], // TODO: validate hex colour
    ];

    /**
     * Whether the request is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}