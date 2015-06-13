<?php namespace Zeropingheroes\Lanager\Domain\Lans;

use Zeropingheroes\Lanager\Domain\ResourceService;

class LanService extends ResourceService  {

	protected $model = 'Zeropingheroes\Lanager\Domain\Lans\Lan';

	protected $orderBy = [ 'start' ];

	protected $eagerLoad = [ 'userAchievements.achievement', 'userAchievements.user.state.application' ];

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole('LANs Admin');
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole('LANs Admin');
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole('LANs Admin');
	}

	protected function validationRulesOnStore( $input )
	{
		return [
			'name'		=> [ 'required', 'max:255', 'unique:lans,name' ],
			'start'		=> [ 'required', 'date_format:Y-m-d H:i:s', 'before:end' ],
			'end'		=> [ 'required', 'date_format:Y-m-d H:i:s', 'after:start' ],
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		$rules = $this->validationRulesOnStore( $input );

		// Exclude current event type from uniqueness test
		$rules['name'] = [ 'required', 'max:255', 'unique:lans,name,' . $input['id'] ];

		return $rules;
	}

}