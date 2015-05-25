<?php namespace Zeropingheroes\Lanager\Domain\Users;

use Zeropingheroes\Lanager\Domain\ServiceUserContract;
use Eloquent;

class EloquentServiceUserAdapter implements ServiceUserContract {

	protected $user = null;

	protected $id = null;

	public function __construct( Eloquent $user = null )
	{
		if ( $user )
		{
			$this->user = $user;
			$this->id = $this->user->id;			
		}
	}

	public function id()
	{
		return $this->id;
	}

	public function isAuthenticated()
	{
		return ! empty( $this->user );
	}

	public function hasRole( $role )
	{
		if ( $this->user )
			return $this->user->hasRole( $role );
		return false;
	}


}