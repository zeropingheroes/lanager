<?php namespace Zeropingheroes\Lanager\Domain\Pages;

use Zeropingheroes\Lanager\Domain\ResourceService;

class PageService extends ResourceService {

	protected $orderBy = [ 'position' ];

	public function __construct()
	{
		parent::__construct(
			new Page,
			new PageValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole( 'Pages Admin' );
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole( 'Pages Admin' );
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole( 'Pages Admin' );
	}

	protected function filter()
	{
		if ( ! $this->user->hasRole( 'Pages Admin' ) )
			$this->model = $this->model->where( 'published', true );
	}

}