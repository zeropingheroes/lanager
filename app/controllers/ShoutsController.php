<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Shouts\Shout;
use Input, Redirect, View, Auth, Request, Response;

class ShoutsController extends BaseController {

	public function __construct()
	{
		// Check if user can access requested method
		$this->beforeFilter('checkResourcePermission',array('only' => array('create', 'store', 'edit', 'update', 'destroy') ));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if ( Request::ajax() ) return Response::json(Shout::with('user', 'user.roles')->get());
		$shouts = Shout::with('user', 'user.roles')
				->orderBy('pinned', 'desc')
				->orderBy('created_at', 'desc')
				->paginate(10);
		return View::make('shouts.index')
					->with('title', 'Shouts')
					->with('shouts', $shouts);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$shout = new Shout;
		$shout->user_id = Auth::user()->id;
		$shout->content = Input::get('content');

		return $this->process( $shout, 'shouts.index', 'shouts.index' );		

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Redirect::route('shouts.index');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$shout = Shout::findOrFail($id);

		if( Input::has('user_id') )	$shout->user_id	= User::findOrFail(Input::get('user_id'))->id;
		if( Input::has('content') )	$shout->content = Input::get('content');
		if( Input::has('pinned') )	$shout->pinned = (int) Input::get('pinned');

		return $this->process( $shout, 'shouts.index', 'shouts.index' );		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$shout = Shout::findOrFail($id);

		return $this->process( $shout );
	}

}