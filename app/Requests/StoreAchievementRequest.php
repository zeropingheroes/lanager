<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreAchievementRequest extends Request
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
            'name' => ['required', 'max:255'],
            'description' => ['nullable'],
            'image'  => ['nullable', 'image', 'max:5000'],
        ];

        $this->validationMessages = [
            'image.image'    => __('phrase.submitted-file-was-invalid-image'),
            'image.max'  => __('phrase.submitted-file-exceeded-max-file-size-of-x', ['x' => '5MB']),
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}