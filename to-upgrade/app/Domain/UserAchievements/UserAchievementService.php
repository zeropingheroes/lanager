<?php namespace Zeropingheroes\Lanager\Domain\UserAchievements;

use Zeropingheroes\Lanager\Domain\ResourceService;

class UserAchievementService extends ResourceService
{

    protected $model = 'Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievement';

    protected $orderBy = [['lan_id', 'desc'], ['user_id', 'desc']];

    protected $eagerLoad = ['achievement', 'lan', 'user.state.application'];

    protected function readAuthorised()
    {
        return true;
    }

    protected function storeAuthorised()
    {
        return $this->user->hasRole('Achievements Admin');
    }

    protected function updateAuthorised()
    {
        return $this->user->hasRole('Achievements Admin');
    }

    protected function destroyAuthorised()
    {
        return $this->user->hasRole('Achievements Admin');
    }

    protected function validationRulesOnStore($input)
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'achievement_id' => ['required', 'exists:achievements,id'],
            'lan_id' => ['required', 'exists:lans,id'],
        ];
    }

    protected function validationRulesOnUpdate($input)
    {
        return $this->validationRulesOnStore($input);
    }

}