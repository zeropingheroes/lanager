<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Shout;
use Input, Redirect, View, Auth;

class ShoutsController extends BaseController {

	public function __construct()
	{
		// Check if user can access requested method
		$this->beforeFilter('checkResourcePermission',array('only' => array('create', 'store', 'edit', 'update', 'destroy') ));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$shouts = Shout::with('user', 'user.roles')
						->orderBy('pinned', 'desc')
						->orderBy('created_at', 'desc')
						->paginate(10);
		return View::make('shouts.index')
					->with('title', 'Shouts')
					->with('shouts', $shouts);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$shout = new Shout;
		$shout->user_id = Auth::user()->id;
		$shout->content = Input::get('content');

		return $this->process( $shout, 'shouts.index', 'shouts.index' );		

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return Redirect::route('shouts.index');
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
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$shout = Shout::findOrFail($id);

		return $this->process( $shout );
	}

	/**
	 * Toggle the "pinned" flag on the shouts.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function pin($id)
	{
		if( $shout = Shout::find($id))
		{
			$shout->pinned = !$shout->pinned;
			$shout->save();
		}
		return Redirect::route('shouts.index');
	}

}