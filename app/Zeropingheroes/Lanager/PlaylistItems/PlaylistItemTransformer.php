<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use League\Fractal;

class PlaylistItemTransformer extends Fractal\TransformerAbstract {
	
	public function transform(PlaylistItem $playlistItem)
	{
		return [
			'id'			=> (int) $playlistItem->id,
			'playlist_id'	=> (int) $playlistItem->playlist_id,
			'title'			=> $playlistItem->title,
			'url'			=> $playlistItem->url,
			'duration'		=> $playlistItem->duration,
			'playback_state'=> (int) $playlistItem->playback_state,
			'skip_reason'	=> $playlistItem->skip_reason,
			'played'		=> date('c',strtotime($playlistItem->played_at)),
			'user'			=> [
				'id'			=> $playlistItem->user->id,
				'username'		=> $playlistItem->user->username,
				'avatar'		=> $playlistItem->user->avatar,
			],
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/playlists/'. $playlistItem->playlist_id .'/items/'. $playlistItem->id),
				]
			],
		];
	}
}