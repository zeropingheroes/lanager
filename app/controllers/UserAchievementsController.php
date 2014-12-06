<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\UserAchievements\UserAchievement,
	Zeropingheroes\Lanager\Achievements\Achievement,
	Zeropingheroes\Lanager\Lans\Lan,
	Zeropingheroes\Lanager\Users\User;
use View, Input, Redirect, Request, Response;

class UserAchievementsController extends BaseController {

	
	public function __construct()
	{
		$this->beforeFilter('permission', ['only' => ['create', 'store', 'edit', 'update', 'destroy'] ]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$userAchievement = new UserAchievement;
		$users = User::visible()
						->orderBy('username')
						->lists('username','id');

		$achievements = Achievement::orderBy('name')
						->lists('name','id');

		$lans = Lan::orderBy('start','desc')
						->lists('name','id');

		return View::make('user-achievements.create')
					->with('title','Award Achievement')
					->with('userAchievement',$userAchievement)
					->with('users',$users)
					->with('achievements',$achievements)
					->with('lans',$lans);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$userAchievement = new UserAchievement;
		$userAchievement->fill( Input::get() );

		if ( ! $this->save($userAchievement) ) return Redirect::back()->withInput();

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
		$userAchievement = UserAchievement::findOrFail($id);

		return $this->process( $userAchievement, 'achievements.index' );
	}

}