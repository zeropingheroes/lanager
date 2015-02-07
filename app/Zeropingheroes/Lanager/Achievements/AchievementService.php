<?php namespace Zeropingheroes\Lanager\Achievements;

use Zeropingheroes\Lanager\BaseResourceService;

class AchievementService extends BaseResourceService {

	public $resourceName = 'achievement';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new Achievement;
	}

}