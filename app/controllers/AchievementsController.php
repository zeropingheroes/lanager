<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Achievement;
use View, Input, Redirect, Request, Response, URL, Auth, Authority;

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
		if ( Request::ajax() ) return Response::json(Achievement::all());

		if ( Authority::can('manage', 'achievements') && Input::get('hidden') == true )
		{
			$achievements = Achievement::with(array('users' => function($query)
											{
												$query->where('visible', true);
											}))
											->where('visible', false);
		}
		else
		{
			$achievements = Achievement::with(array('users' => function($query)
											{
												$query->where('visible', true);
											}))
										->orWhere(function($q)
											{
												$q->orWhere('visible',1);
												$q->orHas('awards');
											});
		}
		$achievements = $achievements->orderBy('name', 'asc')
									->paginate(10);


		return View::make('achievements.list')
					->with('title','Achievements')
					->with('achievements',$achievements);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$achievement = new Achievement;
		return View::make('achievements.create')
					->with('title','Create Achievement')
					->with('achievement',$achievement);
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
		$achievement->description = Input::get('description');
		if( Input::has('visible') && Input::get('visible') == 1) $achievement->visible = 1;
		if( ! Input::has('visible') OR Input::get('visible') == 0) $achievement->visible = 0;

		if ( ! $achievement->save() )
		{
			if ( Request::ajax() ) return Response::json($achievement->errors(), 400);
			
			return Redirect::route('achievements.create', array('achievement' => $achievement->id))->withErrors($achievement->errors());
		}

		if ( Request::ajax() ) return Response::json($achievement, 201);

		return Redirect::route('achievements.index');
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
		$achievement = Achievement::find($id);

		return View::make('achievements.edit')
					->with('title','Edit Achievement')
					->with('achievement',$achievement);
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
		if( Input::has('description') ) $achievement->description = Input::get('description');

		if( Input::has('visible') && Input::get('visible') == 1) $achievement->visible = 1;
		if( ! Input::has('visible') OR Input::get('visible') == 0) $achievement->visible = 0;

		if ( ! $achievement->save() )
		{
			if ( Request::ajax() ) return Response::json($achievement->errors(), 400);
			
			return Redirect::route('achievements.edit', array('achievement' => $achievement->id))->withErrors($achievement->errors());
		}

		if ( Request::ajax() ) return Response::json($achievement);

		return Redirect::route('achievements.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$achievement = Achievement::findOrFail($id)->destroy($id);

		if ( Request::ajax() ) return Response::json( $achievement, 204);

		return Redirect::back();
	}

}