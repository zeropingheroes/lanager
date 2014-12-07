<?php namespace Zeropingheroes\Lanager\PlaylistItems;

use Illuminate\Support\Contracts\ArrayableInterface;

abstract class PlayableItem implements ArrayableInterface {

	protected $url;
	protected $title;
	protected $duration;

	public function toArray()
	{
		return [
			'url'		=> $this->url,
			'title'		=> $this->title,
			'duration'	=> $this->duration,
		];
	}

}