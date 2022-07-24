<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreUserAchievementRequest extends Request
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
            'user_id' => ['required', 'exists:users,id'],
            'achievement_id' => ['required', 'exists:achievements,id'],
            'lan_id' => ['required', 'exists:lans,id'],
        ];

        if (! $this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
