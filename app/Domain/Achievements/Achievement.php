<?php namespace Zeropingheroes\Lanager\Domain\Achievements;

use Zeropingheroes\Lanager\Domain\BaseModel;

class Achievement extends BaseModel
{

    /**
     * Fields that can be mass assigned
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * A single achievement has many user achievements (aka awards)
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function userAchievements()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievement');
    }

}