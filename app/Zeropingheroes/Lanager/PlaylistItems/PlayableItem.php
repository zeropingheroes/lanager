<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use Illuminate\Support\Contracts\ArrayableInterface;

abstract class PlayableItem implements ArrayableInterface {

	/**
	 * Item's canonical URL
	 * @var string
	 */
	protected $url;

	/**
	 * Item's text title or description
	 * @var string
	 */
	protected $title;

	/**
	 * Item's duration in seconds
	 * @var [type]
	 */
	protected $duration;

	/**
	 * Cast the playable item object to an array
	 * @return [type] [description]
	 */
	public function toArray()
	{
		return [
			'url'		=> $this->url,
			'title'		=> $this->title,
			'duration'	=> (int) $this->duration,
		];
	}

}