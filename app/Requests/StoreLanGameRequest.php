<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreLanGameRequest extends Request
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
            'lan_id'    => ['exists:lans,id'],
            'game_name' => ['required', 'max:255'],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
