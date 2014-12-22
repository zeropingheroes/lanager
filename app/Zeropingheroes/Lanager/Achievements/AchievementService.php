<?php namespace Zeropingheroes\Lanager\Achievements;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class AchievementService extends BaseResourceService implements ResourceServiceContract {

	public $resourceName = 'achievement';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new Achievement;
	}

}