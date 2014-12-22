<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Pages\PageService;
use View;

class PagesController extends BaseController {

	protected $route = 'pages';

	public function __construct()
	{
		parent::__construct();
		$this->service = new PageService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('pages.index')
					->with('title','Info')
					->with('pages', $this->service->all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('pages.create')
					->with('title','Create Page')
					->with('pages',['' => ' '] + $this->service->lists('title', 'id'))
					->with('page',null);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$page = $this->service->single($id);

		return View::make('pages.show')
					->with('title',$page->title)
					->with('page',$page);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('pages.edit')
					->with('title','Edit Page')
					->with('pages',['' => ' '] + $this->service->lists('title', 'id'))
					->with('page',$this->service->single($id));
	}

}