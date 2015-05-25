<?php namespace Zeropingheroes\Lanager\Domain\Playlists;

use League\Fractal\TransformerAbstract;
use Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItemTransformer;

class PlaylistTransformer extends TransformerAbstract {

	/**
	 * Default related resources to include in transformed output
	 * @var array
	 */
	protected $defaultIncludes = [
		'currentItem',
	];

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
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
					'uri' => (url().'/playlists/'. $playlist->id),
				]
			],
		];
	}


	/**
	 * Pull in and transform the specified resource
	 * @param  object BaseModel   Model being pulled in
	 * @return array              Transformed model
	 */
	public function includeCurrentItem( Playlist $playlist )
	{
		$currentItem = $playlist->currentItem();

		if ( $currentItem )
			return $this->item( $currentItem, new PlaylistItemTransformer );
	}

}