<?php

namespace Zeropingheroes\Lanager\Requests;

use Illuminate\Database\Eloquent\Relations\Relation;

class StoreUserFavouriteGameRequest extends Request
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
            'id'               => ['required', 'integer'],
            'provider'         => ['required', 'string'],
        ];

        if (!$this->laravelValidationPasses()) {
            return $this->setValid(false);
        }

        $providerModel = Relation::getMorphedModel($this->input['provider']);

        if(!class_exists($providerModel)) {
            $this->addError(__('phrase.unsupported-provider'));
            return $this->setValid(false);
        }

        if(!$providerModel::find($this->input['id'])) {
            $this->addError(__('phrase.item-not-found', ['item' => __('title.game')]));
            return $this->setValid(false);
        }

        if($this->input['user']->favouriteGames()->where('favouriteable_id', $this->input['id'])->first()) {
            $this->addError(__('phrase.game-already-favourited'));
            return $this->setValid(false);
        }
        return $this->setValid(true);
    }
}