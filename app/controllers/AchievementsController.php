<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Achievements\AchievementService;
use View;

class AchievementsController extends BaseController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'achievements';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new AchievementService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$eagerLoad = ['userAchievements'];

		$achievements = $this->service->all( [], $eagerLoad );

		return View::make('achievements.index')
					->with('title','Achievements')
					->with('achievements', $achievements );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('achievements.create')
					->with('title','Create Achievement')
					->with('achievement',null);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$eagerLoad = [
			'userAchievements',
			'userAchievements.lan',
			'userAchievements.user.state.application',
			'userAchievements.user.state.server',
			];

		$achievement = $this->service->single($id, $eagerLoad);
		
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
		return View::make('achievements.edit')
					->with('title','Edit Achievement')
					->with('achievement', $this->service->single($id) );
	}

}