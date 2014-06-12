<?php namespace Zeropingheroes\Lanager\Handlers;

use Zeropingheroes\Lanager\Models\Playlist\Item;
use DB, DateTime;

class PlaylistItemVoteHandler {

	/**
	* Handle user logout events.
	*/
	public function onCreate($vote)
	{

		$item = Item::find($vote->playlist_item_id);

		// Skip the item if we have met or exceeded the downvote threshold
		$activeSessions = DB::table('sessions')->where('last_activity', '>', time()-600)->count();
		$votesRequired = $item->playlist->user_skip_threshold * $activeSessions;
		$itemVotes = abs($item->votes()->sum('vote'));

		if( ( $itemVotes + 1) >= $votesRequired ) // if this vote tips the item beyond the threshold
		{
			// skip the item
			$item->playback_state = 2;
			$item->skip_reason = 'Downvoted by users';
			$item->played_at = new DateTime;
			$item->save();
		}

	}

	/**
	* Register the listeners for the subscriber.
	*
	* @param  Illuminate\Events\Dispatcher  $events
	* @return array
	*/
	public function subscribe($events)
	{
		$events->listen('playlist.item.vote.create', 'Zeropingheroes\Lanager\Handlers\PlaylistItemVoteHandler@onCreate');
	}

}
