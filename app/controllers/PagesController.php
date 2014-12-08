<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Pages\Page;
use View, Input, Redirect;

class PagesController extends BaseController {
	
	public function __construct()
	{
		$this->beforeFilter('permission', ['only' => ['create', 'store', 'edit', 'update', 'destroy'] ]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$pages = Page::all();
		
		return View::make('pages.index')
					->with('title','Info')
					->with('pages',$pages);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$pagesDropdown = array('' => ' ') + Page::lists('title','id');
		$page = new Page;
		return View::make('pages.create')
					->with('title','Create Page')
					->with('pagesDropdown',$pagesDropdown)
					->with('page',$page);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$page = new Page;
		$page->fill( Input::get() );

		if ( ! $this->save($page) ) return Redirect::back()->withInput();

		return Redirect::route('pages.show', $page->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$page = Page::findOrFail($id);
		$pageChildren = Page::where('parent_id',$id)->get();

		return View::make('pages.show')
					->with('title',$page->title)
					->with('pages',$pageChildren)
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
		$page = Page::findOrFail($id);
		$pagesDropdown = array('' => ' ') + Page::lists('title','id');
		return View::make('pages.edit')
					->with('title','Edit Page')
					->with('pagesDropdown',$pagesDropdown)
					->with('page',$page);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$page = Page::findOrFail($id);
		$page->fill( Input::get() );

		if ( ! $this->save($page) ) return Redirect::back()->withInput();

		return Redirect::route('pages.show', $page->id);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$page = Page::findOrFail($id);
		$this->delete($page);
		return Redirect::route('pages.index');
	}
}