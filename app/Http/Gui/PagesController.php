<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\BaseResourceService;
use Zeropingheroes\Lanager\Domain\Pages\PageService;
use View;
use Authority;
use Input;
use App;

class PagesController extends ResourceServiceController {

	/**
	 * Based named route used by this resource
	 * @var string
	 */
	protected $route = 'pages';

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new PageService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if( Authority::can('manage', 'pages') )	$pages = $this->service->all();
		if( Authority::cannot('manage', 'pages') )	$pages = $this->service->all( ['published' => true ] );

		return View::make('pages.index')
					->with('title','Info')
					->with('pages', $pages );
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

		if( Authority::cannot('manage', 'pages') AND $page->published == 0 ) App::abort(404);

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