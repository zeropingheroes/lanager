<?php namespace Zeropingheroes\Lanager\Domain\Pages;

use Zeropingheroes\Lanager\Domain\ResourceService;

class PageService extends ResourceService {

	protected $model = 'Zeropingheroes\Lanager\Domain\Pages\Page';

	protected $orderBy = [ 'position' ];

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

	protected function validationRulesOnStore( $input )
	{
		return [
			'title'		=> [ 'required', 'max:255' ],
			'parent_id'	=> [ 'numeric', 'exists:pages,id' ],
			'position'	=> [ 'numeric', 'min:0' ],
			'published'	=> [ 'boolean' ],
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		return $this->validationRulesOnStore( $input );
	}

	protected function domainRulesOnRead( $input )
	{
		if ( ! $this->user->hasRole( 'Pages Admin' ) )
			$this->addFilter( 'where', 'published', true );
	}

}