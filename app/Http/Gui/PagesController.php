<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\Pages\PageService;
use View;
use Redirect;

class PagesController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new PageService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pages = $this->service->all();

		return View::make( 'pages.index' )
					->with( 'title', 'Info' )
					->with( 'pages', $pages );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$pages = ['' => ''] + lists( $this->service->all(), 'id', 'title' );

		return View::make( 'pages.create' )
					->with( 'title','Create Page' )
					->with( 'pages', $pages )
					->with( 'page', null );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show( $id )
	{
		$page = $this->service->single( $id );

		return View::make( 'pages.show' )
					->with( 'title', $page->title )
					->with( 'page', $page );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit( $id )
	{
		$pages = ['' => ''] + lists( $this->service->all(), 'id', 'title' );

		return View::make( 'pages.edit' )
					->with( 'title', 'Edit Page' )
					->with( 'pages', $pages )
					->with( 'page', $this->service->single( $id ) );
	}

	protected function redirectAfterStore()
	{
		return Redirect::route( 'pages.show', $this->service->id() );
	}

	protected function redirectAfterUpdate()
	{
		return $this->redirectAfterStore();
	}

	protected function redirectAfterDestroy()
	{
		return Redirect::route( 'pages.index' );
	}

}