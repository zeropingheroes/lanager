<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Playlists\PlaylistService;
use View;

class PlaylistsController extends BaseController {

	protected $route = 'playlists';

	public function __construct()
	{
		parent::__construct();
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
					->with('title', $playlist->name . ' Playlist Screen')
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
}