<?php namespace Zeropingheroes\Lanager\Domain\EventTypes;

use Laracasts\Presenter\PresentableTrait;
use Zeropingheroes\Lanager\Domain\BaseModel;

class EventType extends BaseModel
{

    use PresentableTrait;

    protected $presenter = 'Zeropingheroes\Lanager\Domain\EventTypes\EventTypePresenter';

    protected $fillable = ['name', 'colour'];

    protected $nullable = ['colour'];

    /**
     * A single event type has many events
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function events()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Domain\Events\Event');
    }

}