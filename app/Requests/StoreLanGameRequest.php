<?php

namespace Zeropingheroes\Lanager\Requests;

use Zeropingheroes\Lanager\LanGame;

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

        $lanGamesWithSameName = LanGame::where([
                ['lan_id', '=', $this->input['lan_id']],
                ['game_name', '=', $this->input['game_name']],
            ])->count();

        if ($lanGamesWithSameName != 0) {
            $this->addError(__('phrase.game-already-submitted'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
