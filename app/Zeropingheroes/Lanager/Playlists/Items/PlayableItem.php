<?php namespace Zeropingheroes\Lanager\Playlists\Items;

abstract class PlayableItem {

	protected $url;
	protected $title;
	protected $duration;

	public function getUrl()
	{
		return $this->url;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getDuration()
	{
		return $this->duration;
	}

}