<?php namespace Zeropingheroes\Lanager\Shouts;

use Zeropingheroes\Lanager\FlatResourceService;
use Auth, Authority;

class ShoutService extends FlatResourceService {

	protected $resource = 'shout';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Shout);
	}

	public function filterInput($input)
	{
		$input['user_id'] = Auth::user()->id;
		
		if( Authority::cannot('manage', 'shouts') ) unset($input['pinned']);
		
		return $input;
	}

	public function store($input)
	{
		$input = $this->filterInput($input);
		return parent::store($input);
	}

	public function update($id, $input)
	{
		$input = $this->filterInput($input);
		return parent::update($id, $input);
	}

}