<?php namespace Zeropingheroes\Lanager\UserAchievements;

use Zeropingheroes\Lanager\FlatResourceService;

class UserAchievementService extends FlatResourceService {

	protected $resource = 'user achievement';

	public function __construct( $listener )
	{
		parent::__construct($listener, new UserAchievement);
	}

}