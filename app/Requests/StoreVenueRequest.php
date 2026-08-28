<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Validation\Rule;

class StoreVenueRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('venues')->ignore($this->input['id'] ?? ''),
            ],
            'street_address' => ['required', 'max:255'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
