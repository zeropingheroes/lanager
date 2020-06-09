<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreGuideRequest extends Request
{
    use LaravelValidation;

    /**
     * Whether the request is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        $this->validationRules = [
            'lan_id' => ['required', 'numeric', 'exists:lans,id'],
            'title' => ['required', 'max:255'],
            'content' => ['nullable'],
            'published' => ['nullable', 'boolean'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
