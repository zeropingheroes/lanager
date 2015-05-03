<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\BaseResourceService;
use Zeropingheroes\Lanager\Domain\Lans\LanService;
use View;

class LansController extends ResourceServiceController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'lans';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new LanService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$eagerLoad = ['userAchievements'];

		$lans = $this->service->all( [], $eagerLoad );

		return View::make('lans.index')
					->with('title','LANs')
					->with('lans', $lans);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('lans.create')
					->with('title','Create LAN')
					->with('lan',null);
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
			'userAchievements.achievement',
			'userAchievements.lan',
			'userAchievements.user.state.application',
			'userAchievements.user.state.server',
		];
		
		$lan = $this->service->single($id, $eagerLoad);

		return View::make('lans.show')
					->with('title', $lan->name)
					->with('lan', $lan);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('lans.edit')
					->with('title','Edit LAN')
					->with('lan',$this->service->single($id));
	}

}