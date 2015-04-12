<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\UserAchievements\UserAchievementService,
	Zeropingheroes\Lanager\UserAchievements\UserAchievementTransformer;

class UserAchievementsController extends BaseController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new UserAchievementService($this);
		$this->transformer = new UserAchievementTransformer;
	}

}