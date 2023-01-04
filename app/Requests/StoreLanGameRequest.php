<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Validation\Rule;

class StoreLanGameRequest extends Request
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
            'lan_id' => ['required', 'exists:lans,id'],
            'game_name' => [
                'required',
                'max:255',
                Rule::unique('lan_games')->where(
                    function ($query) {
                        $query->where('lan_id', $this->input['lan_id']);
                    }
                )->ignore($this->input['id'] ?? '')
            ],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
