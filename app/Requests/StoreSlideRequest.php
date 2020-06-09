<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreSlideRequest extends Request
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
            'name' => ['required', 'max:255'],
            'content' => ['required'],
            'position' => ['integer', 'min:0', 'max:127'],
            'duration' => ['integer', 'min:0', 'max:32767'],
            'start' => ['nullable', 'date_format:Y-m-d H:i:s', 'before:end'],
            'end' => ['nullable', 'date_format:Y-m-d H:i:s', 'after:start'],
            'published' => ['boolean'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
