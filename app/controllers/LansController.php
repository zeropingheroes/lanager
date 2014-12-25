<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Lans\LanService;
use View;

class LansController extends BaseController {

	protected $route = 'lans';

	public function __construct()
	{
		parent::__construct();
		$this->service = new LanService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('lans.index')
					->with('title','LANs')
					->with('lans', $this->service->all());
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
		$lan = $this->service->single($id);

		return View::make('lans.show')
					->with('title',$lan->name)
					->with('lan',$lan);
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