<?php namespace Zeropingheroes\Lanager\Domain\Shouts;

use Zeropingheroes\Lanager\Domain\BaseModel;

class Shout extends BaseModel
{

    protected $fillable = ['user_id', 'content', 'pinned'];

    /**
     * A single shout belongs to a single user
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
    }

}