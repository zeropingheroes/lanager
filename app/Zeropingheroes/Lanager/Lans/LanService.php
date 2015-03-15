<?php namespace Zeropingheroes\Lanager\Lans;

use Zeropingheroes\Lanager\FlatResourceService;

class LanService extends FlatResourceService  {

	protected $resource = 'lans';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Lan);
	}

}