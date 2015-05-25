<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\BaseResourceService;
use Zeropingheroes\Lanager\Domain\Playlists\PlaylistService;
use View;
use Redirect;

class PlaylistsController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new PlaylistService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('playlists.index')
					->with('title', 'Playlists')
					->with('playlists', $this->service->all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('playlists.create')
					->with('title','Create Playlist')
					->with('playlist',null);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function show($playlistId)
	{
		$playlist = $this->service->single($playlistId);

		return View::make('playlists.show')
					->with('title', 'Playlist - ' . $playlist->name)
					->with('playlist', $playlist);				
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function edit($playlistId)
	{
		return View::make('playlists.edit')
					->with('title','Edit Playlist')
					->with('playlist',$this->service->single($playlistId));
	}

	/**
	 * Play the specified playlist.
	 *
	 * @param  int  $playlistId
	 * @return Response
	 */
	public function play($playlistId)
	{
		$playlist = $this->service->single($playlistId);

		return View::make('playlists.play')
					->with('title', 'Playlist - ' . $playlist->name)
					->with('playlist', $playlist);				
	}

	protected function redirectAfterStore()
	{
		return Redirect::route('playlists.show', $this->service->id() );
	}

	protected function redirectAfterUpdate()
	{
		return $this->redirectAfterStore();
	}

	protected function redirectAfterDestroy()
	{
		return Redirect::route('playlists.index');
	}

}