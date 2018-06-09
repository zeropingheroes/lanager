<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreImageRequest extends Request
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
            'images'    => ['required'],
            'images.*'  => ['required', 'image', 'max:5000'],
        ];

        $this->validationMessages = [
            'images.*.image'    => __('phrase.submitted-file-was-invalid-image'),
            'images.*.max'  => __('phrase.submitted-file-exceeded-max-file-size-of-x', ['x' => '5MB']),
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}