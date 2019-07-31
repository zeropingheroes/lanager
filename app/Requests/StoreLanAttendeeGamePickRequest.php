<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Database\Eloquent\Relations\Relation;
use Zeropingheroes\Lanager\LanAttendeeGamePick;

class StoreLanAttendeeGamePickRequest extends Request
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
            'lan_id'                => ['required', 'integer', 'exists:lans,id'],
            'user_id'               => ['required', 'integer', 'exists:users,id'],
            'game_id'               => ['required', 'integer'],
            'game_provider'         => ['required', 'string'],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $providerModel = Relation::getMorphedModel($this->input['game_provider']);

        if(!class_exists($providerModel)) {
            $this->addError(__('phrase.unsupported-provider'));
            return $this->setValid(false);
        }

        if(!$providerModel::find($this->input['game_id'])) {
            $this->addError(__('phrase.item-not-found', ['item' => __('title.game')]));
            return $this->setValid(false);
        }

        $alreadyPicked = LanAttendeeGamePick::where([
            ['user_id',          '=', $this->input['user_id']],
            ['lan_id',           '=', $this->input['lan_id']],
            ['game_id',          '=', $this->input['game_id']],
            ['game_provider',    '=', $this->input['game_provider']],
        ])->count();

        if($alreadyPicked) {
            $this->addError(__('phrase.game-already-picked'));
            return $this->setValid(false);
        }
        return $this->setValid(true);
    }
}