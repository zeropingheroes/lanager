<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Achievement;
use View, Input, Redirect, Request, Response, URL, Auth;

class AchievementsController extends BaseController {

	
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
		$achievement = Achievement::all();
		
		if ( Request::ajax() ) return Response::json($achievement);	}

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
		$achievement = new Achievement;

		$achievement->name = Input::get('name');
		$achievement->image = Input::get('image');
		$achievement->visible = Input::get('visible');

		if ( ! $achievement->save() )
		{
			if ( Request::ajax() ) return Response::json($achievement->errors(), 400);
			
			return Redirect::route('achievements.create', array('achievement' => $achievement->id))->withErrors($achievement->errors());
		}

		if ( Request::ajax() ) return Response::json($achievement, 201);

		return Redirect::route('achievements.items.index',array('achievement' => $achievement->id));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$achievement = Achievement::findOrFail($id);
		
		if ( Request::ajax() ) return Response::json($achievement);		
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
		$achievement = Achievement::findOrFail($id);

		if( Input::has('name') ) $achievement->name = Input::get('name');
		if( Input::has('image') ) $achievement->image = Input::get('image');
		if( Input::has('visible') ) $achievement->visible = Input::get('visible');
		
		if ( ! $achievement->save() )
		{
			if ( Request::ajax() ) return Response::json($achievement->errors(), 400);
			
			return Redirect::route('achievements.edit', array('achievement' => $achievement->id))->withErrors($achievement->errors());
		}

		if ( Request::ajax() ) return Response::json($achievement);

		return Redirect::route('achievements.items.index',array('achievement' => $achievement->id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$achievement = Achievement::findOrFail($id);

		if ( Request::ajax() ) return Response::json( $achievement->destroy($id), 204);
	}

}