<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Achievements\Achievement,
	Zeropingheroes\Lanager\Achievements\AchievementValidator;
use View, Input, Redirect, Notification;

class AchievementsController extends BaseController {
	
	public function __construct()
	{
		$this->beforeFilter('permission', ['only' => ['create', 'store', 'edit', 'update', 'destroy'] ] );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$achievements = Achievement::orderBy('name', 'asc')
									->paginate(10);

		return View::make('achievements.index')
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
		$achievement->fill( Input::get() );

		if ( ! $this->save($achievement) ) return Redirect::back()->withInput();
		
		return Redirect::route('achievements.show', $achievement->id);
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
		
		return View::make('achievements.show')
					->with('title', 'Achievement - ' . $achievement->name)
					->with('achievement',$achievement);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$achievement = Achievement::findOrFail($id);

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
		$achievement->fill( Input::get() );

		if ( ! $this->save($achievement) ) return Redirect::back()->withInput();

		return Redirect::route('achievements.show', $achievement->id);
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
		$this->delete($achievement);
		return Redirect::route('achievements.index');
	}

}