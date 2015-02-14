<?php namespace Zeropingheroes\Lanager\Lans;

use Zeropingheroes\Lanager\FlatResourceService;

class LanService extends FlatResourceService  {

	protected $resource = 'lan';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Lan);
	}

}