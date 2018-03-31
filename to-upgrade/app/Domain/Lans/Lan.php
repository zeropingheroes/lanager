<?php namespace Zeropingheroes\Lanager\Domain\Lans;

use Laracasts\Presenter\PresentableTrait;
use Zeropingheroes\Lanager\Domain\BaseModel;

class Lan extends BaseModel
{

    use PresentableTrait;

    protected $presenter = 'Zeropingheroes\Lanager\Domain\Lans\LanPresenter';

    protected $fillable = ['name', 'start', 'end'];

    /**
     * A single LAN has many user achievements
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function userAchievements()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievement');
    }

}