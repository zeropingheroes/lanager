<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Playlists\PlaylistService;
use View, Notification, Redirect;

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

	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.items.index', $service->resourceIds() );
	}

	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.items.index', $service->resourceIds() );
	}

}