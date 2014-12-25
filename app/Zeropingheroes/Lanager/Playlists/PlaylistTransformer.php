<?php namespace Zeropingheroes\Lanager\Playlists;

use League\Fractal;

class PlaylistTransformer extends Fractal\TransformerAbstract {
	
	public function transform(Playlist $playlist)
	{
		return [
			'id'					=> (int) $playlist->id,
			'name'					=> $playlist->name,
			'description'			=> $playlist->description,
			'playback_state'		=> (int) $playlist->playback_state,
			'max_item_duration'		=> (int) $playlist->max_item_duration,
			'user_skip_threshold'	=> (int) $playlist->user_skip_threshold,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/playlists/'. $playlist->id),
				]
			],
		];
	}
}