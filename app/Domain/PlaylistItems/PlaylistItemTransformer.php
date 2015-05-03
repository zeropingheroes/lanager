<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItems;

use League\Fractal\TransformerAbstract;
use Zeropingheroes\Lanager\Domain\Users\UserTransformer;
use Zeropingheroes\Lanager\Domain\Playlists\PlaylistTransformer;

class PlaylistItemTransformer extends TransformerAbstract {

	/**
	 * Default related resources to include in transformed output
	 * @var array
	 */
	protected $defaultIncludes = [
		'user',
		'playlist',
	];

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform( PlaylistItem $playlistItem )
	{
		return [
			'id'			=> (int) $playlistItem->id,
			'playlist_id'	=> (int) $playlistItem->playlist_id,
			'title'			=> $playlistItem->title,
			'url'			=> $playlistItem->url,
			'duration'		=> $playlistItem->duration,
			'playback_state'=> (int) $playlistItem->playback_state,
			'skip_reason'	=> $playlistItem->skip_reason,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/playlists/'. $playlistItem->playlist_id .'/items/'. $playlistItem->id),
				]
			],
		];
	}

	/**
	 * Pull in and transform the specified resource
	 * @param  object BaseModel   Model being pulled in
	 * @return array              Transformed model
	 */
	public function includeUser( PlaylistItem $playlistItem )
	{
		return $this->item($playlistItem->user()->first(), new UserTransformer);
	}

	/**
	 * Pull in and transform the specified resource
	 * @param  object BaseModel   Model being pulled in
	 * @return array              Transformed model
	 */
	public function includePlaylist( PlaylistItem $playlistItem )
	{
		return $this->item($playlistItem->playlist()->first(), new PlaylistTransformer);
	}
}