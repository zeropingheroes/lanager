<?php namespace Zeropingheroes\Lanager\UserAchievements;

use Zeropingheroes\Lanager\FlatResourceService;

class UserAchievementService extends FlatResourceService {

	protected $resource = 'user-achievements';

	public function __construct( $listener )
	{
		parent::__construct($listener, new UserAchievement);
	}

}