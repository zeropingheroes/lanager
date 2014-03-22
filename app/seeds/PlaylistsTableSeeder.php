<?php namespace Zeropingheroes\Lanager\Seeds;

use	Zeropingheroes\Lanager\Models\Playlist;
use Illuminate\Database\Seeder,
	Illuminate\Support\Facades\DB;

class PlaylistsTableSeeder extends Seeder {

	public function run()
	{
		DB::table('playlists')->delete(); // Empty before we seed

		$playlists = array(
			array('name' => 'Default'),
		);

		foreach($playlists as $playlist)
		{
			Playlist::create($playlist);
		}
	}
}
