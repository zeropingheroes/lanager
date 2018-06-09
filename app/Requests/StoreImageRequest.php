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

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}