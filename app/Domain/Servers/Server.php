<?php namespace Zeropingheroes\Lanager\Domain\Servers;

use Laracasts\Presenter\PresentableTrait;
use Zeropingheroes\Lanager\Domain\BaseModel;

class Server extends BaseModel
{

    use PresentableTrait;

    protected $presenter = 'Zeropingheroes\Lanager\Domain\Servers\ServerPresenter';

    protected $fillable = ['application_id', 'name', 'address', 'port', 'pinned'];

    protected $nullable = ['name'];

    /**
     * A single server belongs to a single application
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function application()
    {
        return $this->belongsTo('Zeropingheroes\Lanager\Domain\Applications\Application');
    }

    /**
     * A single server has many states
     * @return object Illuminate\Database\Eloquent\Relations\Relation
     */
    public function states()
    {
        return $this->hasMany('Zeropingheroes\Lanager\Domain\States\State');
    }

}