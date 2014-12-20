<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Pages\Page;
use View;

class PagesController extends BaseController {
	
	protected $resourceService = 'Zeropingheroes\Lanager\Pages\PageService';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('pages.index')
					->with('title','Info')
					->with('pages', $this->resourceService->all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('pages.create')
					->with('title','Create Page')
					->with('pages',['' => ' '] + $this->resourceService->lists(['title', 'id']))
					->with('page',null);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$page = $this->resourceService->single($id);

		return View::make('pages.show')
					->with('title',$page->title)
					->with('page',$page);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('pages.edit')
					->with('title','Edit Page')
					->with('pages',['' => ' '] + $this->resourceService->lists(['title', 'id']))
					->with('page',$this->resourceService->single($id));
	}

}