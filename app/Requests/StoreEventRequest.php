<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreEventRequest extends Request
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
            'name'          => ['required', 'max:255'],
            'start'         => ['required', 'date_format:Y-m-d H:i:s', 'before:end'],
            'end'           => ['required', 'date_format:Y-m-d H:i:s', 'after:start'],
            'event_type_id' => ['required', 'numeric', 'exists:event_types,id'],
            'lan_id'        => ['required', 'numeric', 'exists:lans,id'],
            'published'     => ['boolean'],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}