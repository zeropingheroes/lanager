<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\Achievements\AchievementService;
use View;
use Redirect;

class AchievementsController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new AchievementService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$achievements = $this->service->all();

		return View::make( 'achievements.index' )
					->with( 'title', 'Achievements' )
					->with( 'achievements', $achievements );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make( 'achievements.create' )
					->with( 'title','Create Achievement' )
					->with( 'achievement', null );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show( $id )
	{
		$achievement = $this->service->single( $id );
		
		return View::make( 'achievements.show' )
					->with( 'title', 'Achievement - ' . $achievement->name )
					->with( 'achievement', $achievement );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit( $id )
	{
		$achievement = $this->service->single( $id );

		return View::make( 'achievements.edit' )
					->with( 'title','Edit Achievement' )
					->with( 'achievement', $achievement );
	}

	protected function redirectAfterStore( $resource )
	{
		return Redirect::route('achievements.show', $resource['id'] );
	}

	protected function redirectAfterUpdate( $resource )
	{
		return $this->redirectAfterStore( $resource );
	}

	protected function redirectAfterDestroy( $resource )
	{
		return Redirect::route('achievements.index');
	}

}