<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use League\Fractal;

use Zeropingheroes\Lanager\Users\UserTransformer;

class PlaylistItemTransformer extends Fractal\TransformerAbstract {

	protected $defaultIncludes = [
		'user',
	];

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
			'played_at'		=> ( empty($playlistItem->played_at) ? null : date('c',strtotime($playlistItem->played_at)) ),
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/playlists/'. $playlistItem->playlist_id .'/items/'. $playlistItem->id),
				]
			],
		];
	}

	public function includeUser(PlaylistItem $playlistItem)
	{
		return $this->collection($playlistItem->user()->get(), new UserTransformer);
	}
}