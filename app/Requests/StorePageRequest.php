<?php

namespace Zeropingheroes\Lanager\Requests;

class StorePageRequest extends Request
{
    use LaravelValidation;

    /**
     * Validation rules for the Laravel validator
     *
     * @var array
     */
    protected $laravelValidationRules = [
        'title' => 'required|max:255',
        'content' => 'nullable',
        'published' => 'nullable|boolean',
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