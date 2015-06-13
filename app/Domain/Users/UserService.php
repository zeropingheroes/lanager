<?php namespace Zeropingheroes\Lanager\Domain\Users;

use Zeropingheroes\Lanager\Domain\ResourceService;

class UserService extends ResourceService {

	protected $orderBy = [ 'username' ];

	protected $eagerLoad = [ 'state.application', 'userAchievements', 'roles' ];

	public function __construct()
	{
		parent::__construct(
			new User
		);
	}

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

	protected function filter()
	{
		if ( ! $this->user->hasRole( 'Super Admin' ) )
			$this->model = $this->model->where( 'visible', true );
	}

}