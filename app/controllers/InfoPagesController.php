<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\InfoPages\InfoPage,
	Zeropingheroes\Lanager\InfoPages\InfoPageValidator;
use View, Input, Redirect, Notification;

class InfoPagesController extends BaseController {
	
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
		$infoPages = InfoPage::all();
		
		return View::make('infopages.index')
					->with('title','Info')
					->with('infoPages',$infoPages);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$infoPagesDropdown = array('' => ' ') + InfoPage::lists('title','id');
		$infoPage = new InfoPage;
		return View::make('infopages.create')
					->with('title','Create Info Page')
					->with('infoPagesDropdown',$infoPagesDropdown)
					->with('infoPage',$infoPage);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$infoPage = new InfoPage;

		if( Input::has('title') )		$infoPage->title = Input::get('title');
		if( Input::has('content') )		$infoPage->content = Input::get('content');
		if( Input::has('parent_id') )	$infoPage->parent_id = Input::get('parent_id');

		$infoPageValidator = InfoPageValidator::make( $infoPage->toArray() )->scope('store');

		if ( $infoPageValidator->fails() )
		{
			Notification::danger( $infoPageValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$infoPage->save();
		Notification::success('Info page successfully stored');
		return Redirect::route('infopages.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$infoPage = InfoPage::findOrFail($id);
		$infoPageChildren = InfoPage::where('parent_id',$id)->get();

		return View::make('infopages.show')
					->with('title',$infoPage->title)
					->with('infoPages',$infoPageChildren)
					->with('infoPage',$infoPage);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$infoPage = InfoPage::findOrFail($id);
		$infoPagesDropdown = array('' => ' ') + InfoPage::lists('title','id');
		return View::make('infopages.edit')
					->with('title','Edit Info Page')
					->with('infoPagesDropdown',$infoPagesDropdown)
					->with('infoPage',$infoPage);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$infoPage = InfoPage::findOrFail($id);

		if( Input::has('title') )		$infoPage->title = Input::get('title');
		if( Input::has('content') )		$infoPage->content = Input::get('content');
		if( Input::has('parent_id') )	$infoPage->parent_id = Input::get('parent_id');

		$infoPageValidator = InfoPageValidator::make( $infoPage->toArray() )->scope('update');

		if ( $infoPageValidator->fails() )
		{
			Notification::danger( $infoPageValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$infoPage->save();
		Notification::success('Info page successfully updated');
		return Redirect::route('infopages.show', $infoPage->id);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$infoPage = InfoPage::findOrFail($id);

		$infoPage->delete();
		Notification::success('Info page successfully destroyed');
		return Redirect::back();
	}

}