<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Validation\Rule;

class StoreAchievementRequest extends Request
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
                Rule::unique('achievements')->ignore($this->input['id'] ?? ''),
            ],
            'description' => ['nullable'],
            'image' => ['nullable', 'image', 'max:5000'],
        ];

        $this->validationMessages = [
            'image.image' => trans('phrase.submitted-file-was-invalid-image'),
            'image.max' => trans('phrase.submitted-file-exceeded-max-file-size-of-x', ['x' => '5MB']),
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
