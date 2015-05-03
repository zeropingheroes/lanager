<?php

use	Zeropingheroes\Lanager\Domain\Playlists\Playlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaylistsTableSeeder extends Seeder {

	/**
	 * Seed the playlists table with data
	 */
	public function run()
	{
		if( DB::table('playlists')->count()) return; // don't seed if table is not empty

		$playlists = array(
			array(
				'name' 				=> 'Music',
				'description'		=> 'All that sweet LAN music to set the atmosphere. This playlist may be played as audio only!',
				'playback_state'	=> 1
			),
			array(
				'name' 				=> 'Funny',
				'description'		=> 'Funny videos - what the internet\'s all about',
				'playback_state'	=> 1
			),
			array(
				'name' 				=> 'Weird',
				'description'		=> 'Trippy oddities from the depths of the internet',
				'playback_state'	=> 1
			),
		);

		foreach($playlists as $playlist)
		{
			Playlist::create($playlist);
		}
	}
}
