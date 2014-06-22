<?php namespace Zeropingheroes\Lanager\Seeds;

use	Zeropingheroes\Lanager\Playlists\Playlist;
use Illuminate\Database\Seeder,
	Illuminate\Support\Facades\DB;

class PlaylistsTableSeeder extends Seeder {

	public function run()
	{
		if( DB::table('playlists')->count()) return; // don't seed if table is not empty

		$playlists = array(
			array(
				'name' 				=> '',
				'playback_state'	=> 0
			),
		);

		foreach($playlists as $playlist)
		{
			Playlist::create($playlist);
		}
	}
}
