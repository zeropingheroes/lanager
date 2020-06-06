<?php

namespace Zeropingheroes\Lanager\Requests;

class StoreLanGameVoteRequest extends Request
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
            'lan_game_id'   => ['required', 'numeric', 'exists:lan_games,id'],
            'user_id'       => ['required', 'numeric', 'exists:users,id'],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $lanGame = LanGame::findOrFail($this->input['lan_game_id']);
        $user = User::findOrFail($this->input['user_id']);


        if ($lanGame->votes->contains($user->id)) {
            $this->addError(__('phrase.you-have-already-voted-for-this-game'));
            return $this->setValid(false);
        }

        return $this->setValid(true);
    }
}