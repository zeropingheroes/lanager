<?php namespace Zeropingheroes\Lanager\Domain\Playlists;

use Laracasts\Presenter\Presenter;

class PlaylistPresenter extends Presenter {

	/**
	 * Get the playlist's playback state text based on its status
	 * @return [type] [description]
	 */
	public function playbackStateText()
	{
		switch ($this->playback_state)
		{
			case 0:
				return 'Paused';
			case 1:
				return 'Playing';
			default:
				return 'Unknown';
		}
	}

}