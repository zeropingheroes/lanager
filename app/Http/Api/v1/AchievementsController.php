<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Achievements\AchievementService;
use	Zeropingheroes\Lanager\Domain\Achievements\AchievementTransformer;

class AchievementsController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new AchievementService($this);
		$this->transformer = new AchievementTransformer;
	}

}