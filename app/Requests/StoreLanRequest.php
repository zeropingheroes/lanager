<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreLanRequest extends Request
{
    use LaravelValidation;

    /**
     * Validation rules for the Laravel validator
     *
     * @var array
     */
    protected $laravelValidationRules = [
        'name' => 'required|max:255',
        'start' => 'date_format:"Y-m-d H:i:s"',
        'end' => 'date_format:"Y-m-d H:i:s"',
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

        // TODO: check if LAN start date is before end date

        return $this->setValid(true);
    }
}