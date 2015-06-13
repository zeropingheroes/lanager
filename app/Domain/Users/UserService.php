<?php namespace Zeropingheroes\Lanager\Domain\Users;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByTimestamps;

class UserService extends ResourceService {

	use FilterableByTimestamps;

	protected $model = 'Zeropingheroes\Lanager\Domain\Users\User';

	protected $orderBy = [ 'username' ];

	protected $eagerLoad = [ 'state.application', 'userAchievements', 'roles' ];

	protected function readAuthorised()
	{
		return true;
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole('Super Admin');
	}

	protected function validationRulesOnStore( $input )
	{
		return [
			'username'			=> [ 'required', 'max:32' ],
			'steam_id_64'		=> [ 'required', 'max:17' ],
			'steam_visibility'	=> [ 'required', 'in:0,1,2,3' ],
			'ip'				=> [ 'ip' ],
			'avatar'			=> [ 'url' ],
			'visible'			=> [ 'boolean' ],
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		return $this->validationRulesOnStore( $input );
	}

	protected function domainRulesOnRead( $input )
	{
		if ( ! $this->user->hasRole( 'Super Admin' ) )
			$this->addFilter('where', 'visible', true );
	}

}