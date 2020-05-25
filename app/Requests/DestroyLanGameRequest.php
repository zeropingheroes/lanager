<?php

namespace Zeropingheroes\Lanager\Requests;

use Zeropingheroes\Lanager\LanGame;

class DestroyLanGameRequest extends Request
{
    use LaravelValidation;

    /**
     * Whether the request is valid
     *
     * @return bool
     */
    public function valid(): bool
    {
        $lanGame = LanGame::find($this->input['id']);

        if ($lanGame->votes->count() !== 0) {
            $this->addError(__('phrase.cannot-delete-lan-game-which-others-have-voted-for'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}
