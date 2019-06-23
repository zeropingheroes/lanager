<?php

namespace Zeropingheroes\Lanager;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_filename'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('Zeropingheroes\Lanager\UserAchievement');
    }
}