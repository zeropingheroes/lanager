<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Awards\Award,
	Zeropingheroes\Lanager\Achievements\Achievement,
	Zeropingheroes\Lanager\Lans\Lan,
	Zeropingheroes\Lanager\Users\User;
use View, Input, Redirect, Request, Response;

class AwardsController extends BaseController {

	
	public function __construct()
	{
		$this->beforeFilter('permission',array('only' => array('create', 'store', 'edit', 'update', 'destroy') ));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$awards = Award::onlyVisibleUsers()
						->get();
		
		if ( Request::ajax() ) return Response::json($awards);
		return Redirect::route('achievements.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$award = new Award;
		$users = User::visible()
						->orderBy('username')
						->lists('username','id');

		$achievements = Achievement::orderBy('name')
						->lists('name','id');

		$lans = Lan::orderBy('start','desc')
						->lists('name','id');

		return View::make('awards.create')
					->with('title','Award Achievement')
					->with('users',$users)
					->with('achievements',$achievements)
					->with('lans',$lans)
					->with('award',$award);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$award = new Award;

		$award->user_id			= User::findOrFail(Input::get('user_id'))->id;
		$award->achievement_id	= Achievement::findOrFail(Input::get('achievement_id'))->id;
	
		if( Input::has('lan_id') )
		{
			$award->lan_id = Lan::findOrFail(Input::get('lan_id'))->id;
		}
		
		return $this->process( $award, 'achievements.index' );

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$award = Award::onlyVisibleUsers()->with(array('user','achievement'))->findOrFail($id);
		
		if ( Request::ajax() ) return Response::json($award);
		
		return Redirect::route('achievements.index');

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
		$award = Award::findOrFail($id);

		if( Input::has('user_id') )			$award->user_id			= User::findOrFail(Input::get('user_id'))->id;
		if( Input::has('achievement_id') )	$award->achievement_id	= Achievement::findOrFail(Input::get('achievement_id'))->id;
		if( Input::has('lan_id') )			$award->lan_id 			= Lan::findOrFail(Input::get('lan_id'))->id;

		return $this->process( $award, 'achievements.index' );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$award = Award::findOrFail($id);

		return $this->process( $award, 'achievements.index' );
	}

}