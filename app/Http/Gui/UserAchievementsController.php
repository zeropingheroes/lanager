<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\BaseResourceService;
use Zeropingheroes\Lanager\Domain\UserAchievements\UserAchievementService;
use Zeropingheroes\Lanager\Domain\Users\UserService;
use Zeropingheroes\Lanager\Domain\Achievements\AchievementService;
use Zeropingheroes\Lanager\Domain\Lans\LanService;
use Zeropingheroes\Lanager\Domain\NotificationListener;

use View;
use Notification;
use Redirect;

class UserAchievementsController extends ResourceServiceController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'user-achievements';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new UserAchievementService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$eagerLoad =
		[
			'lan',
			'achievement',
			'user.state.application',
			'user.state.server',
		];
		$userAchievements = $this->service->all([], $eagerLoad);

		return View::make('user-achievements.index')
					->with('title','User Achievements')
					->with('userAchievements', $userAchievements);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$users 			= (new UserService((new NotificationListener)))->lists('username', 'id');
		$achievements 	= (new AchievementService((new NotificationListener)))->lists('name', 'id');
		$lans 			= (new LanService((new NotificationListener)))->lists('name', 'id');

		return View::make('user-achievements.create')
					->with('title','Award Achievement')
					->with('userAchievement',null)
					->with('users',$users)
					->with('achievements',$achievements)
					->with('lans',$lans);
	}

	/**
	 * Show the form for editing an existing resource.
	 *
	 * @return Response
	 */
	public function edit( $userAchievementId )
	{
		$userAchievement = $this->service->single($userAchievementId);

		$users 			= (new UserService((new NotificationListener)))->lists('username', 'id');
		$achievements 	= (new AchievementService((new NotificationListener)))->lists('name', 'id');
		$lans 			= (new LanService((new NotificationListener)))->lists('name', 'id');

		return View::make('user-achievements.edit')
					->with('title','Award Achievement')
					->with('userAchievement',$userAchievement)
					->with('users',$users)
					->with('achievements',$achievements)
					->with('lans',$lans);
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index' );
	}

	/**
	 * Override listener function for this resource action result
	 * @param  BaseResourceService $service Service class that called this
	 * @return object Response
	 */
	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index' );
	}
}