<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreWhitelistedIpRangeRequest extends Request
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
            'ip_range'           => ['required', 'max:255', 'regex:^([0-9]{1,3}\.){3}[0-9]{1,3}(\/([0-9]|[1-2][0-9]|3[0-2]))?$/g'],
            'description'        => ['nullable', 'max:255'],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}