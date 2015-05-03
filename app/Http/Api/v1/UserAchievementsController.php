<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievementService;
use Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievementTransformer;

class UserAchievementsController extends ResourceServiceController {

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