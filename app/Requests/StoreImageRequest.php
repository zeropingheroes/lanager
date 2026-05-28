<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreImageRequest extends Request
{
    use LaravelValidation;

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function valid(): bool
    {
        $this->validationRules = [
            'images' => ['required'],
            'images.*' => ['required', 'image', 'max:40000'],
        ];

        $this->validationMessages = [
            'images.*.image' => trans('phrase.submitted-file-was-invalid-image'),
            'images.*.max' => trans('phrase.submitted-file-exceeded-max-file-size-of-x', ['x' => '40MB']),
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
