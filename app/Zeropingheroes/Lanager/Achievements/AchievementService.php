<?php namespace Zeropingheroes\Lanager\Achievements;

use Zeropingheroes\Lanager\FlatResourceService;

class AchievementService extends FlatResourceService {

	protected $resource = 'achievements';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Achievement);
	}

}