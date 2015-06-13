<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievementService;
use Zeropingheroes\Lanager\Domain\Users\UserService;
use Zeropingheroes\Lanager\Domain\Achievements\AchievementService;
use Zeropingheroes\Lanager\Domain\Lans\LanService;
use View;
use Redirect;

class UserAchievementsController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new UserAchievementService;
		$this->users 			= lists( (new UserService)->all(), 'id', 'username' );
		$this->achievements 	= lists( (new AchievementService)->all(), 'id', 'name' );
		$this->lans 			= lists( (new LanService)->all(), 'id', 'name' );

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$userAchievements = $this->service->all();

		return View::make( 'user-achievements.index' )
					->with( 'title', 'User Achievements' )
					->with( 'userAchievements', $userAchievements );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make( 'user-achievements.create' )
					->with( 'title', 'Award Achievement' )
					->with( 'userAchievement', null )
					->with( 'users', $this->users )
					->with( 'achievements', $this->achievements )
					->with( 'lans', $this->lans );
	}

	/**
	 * Show the form for editing an existing resource.
	 *
	 * @return Response
	 */
	public function edit( $userAchievementId )
	{
		$userAchievement = $this->service->single( $userAchievementId );

		return View::make( 'user-achievements.edit' )
					->with( 'title', 'Award Achievement' )
					->with( 'userAchievement', $userAchievement )
					->with( 'users', $this->users )
					->with( 'achievements', $this->achievements )
					->with( 'lans', $this->lans );
	}

	protected function redirectAfterStore( $resource )
	{
		return Redirect::route('user-achievements.index' );
	}

	protected function redirectAfterUpdate( $resource )
	{
		return $this->redirectAfterStore( $resource );
	}

	protected function redirectAfterDestroy( $resource )
	{
		return $this->redirectAfterStore( $resource );
	}

}