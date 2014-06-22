<?php namespace Zeropingheroes\Lanager\Playlists\Items\Votes;

use Zeropingheroes\Lanager\Playlists\Items\Item;
use DB, DateTime;

class VoteHandler {

	/**
	* Register the listeners for the subscriber.
	*
	* @param  Illuminate\Events\Dispatcher  $events
	* @return array
	*/
	public function subscribe($events)
	{
		$events->listen('lanager.playlists.items.votes.store', 'Zeropingheroes\Lanager\Playlists\Items\Votes\VoteHandler@onStore');
	}

	public function onStore($vote)
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

}
