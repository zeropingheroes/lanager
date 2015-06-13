<?php namespace Zeropingheroes\Lanager\Domain\Achievements;

use Zeropingheroes\Lanager\Domain\ResourceService;

class AchievementService extends ResourceService {

	protected $orderBy = [ 'name' ];

	protected $eagerLoad = [ 'userAchievements', 'userAchievements.lan', 'userAchievements.user.state.application' ];

	protected $model = 'Zeropingheroes\Lanager\Domain\Achievements\Achievement';

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole('Achievements Admin');
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole('Achievements Admin');
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole('Achievements Admin');
	}

	protected function validationRulesOnStore( $input )
	{
		return [
			'name'			=> [ 'required', 'max:255', 'unique:achievements,name' ],
			'description'	=> [ 'required', 'max:255' ],
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		$rules = $this->validationRulesOnStore( $input );

		// Exclude current achievement from uniqueness test
		$rules['name'] = [ 'required', 'max:255', 'unique:achievements,name,' . $input['id'] ];

		return $rules;
	}

	protected function domainRulesOnRead( $input )
	{
		// Todo: re-add visible field to achievements table
		//if ( ! $this->user->hasRole( 'Achievements Admin' ) )
			//$this->addFilter( 'where', 'visible', true );
	}
}