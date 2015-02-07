<?php namespace Zeropingheroes\Lanager\Achievements;

use Zeropingheroes\Lanager\SingularResourceService;

class AchievementService extends SingularResourceService {

	protected $resource = 'achievement';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Achievement);
	}

}