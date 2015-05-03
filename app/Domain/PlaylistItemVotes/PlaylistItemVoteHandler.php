<?php namespace Zeropingheroes\Lanager\Domain\PlaylistItemVotes;

use Zeropingheroes\Lanager\Domain\PlaylistItems\PlaylistItem;
use DB;
use DateTime;
use Log;

class PlaylistItemVoteHandler {

	/**
	* Register the listeners for the subscriber.
	*
	* @param  Illuminate\Events\Dispatcher  $events
	* @return array
	*/
	public function subscribe( $events )
	{
		$events->listen('lanager.services.playlists.items.votes.store.succeeded', 'Zeropingheroes\Lanager\Domain\PlaylistItemVotes\PlaylistItemVoteHandler@onStore');
	}

	/**
	 * Check if we need to skip the playlist item after the playlist item vote has been stored 
	 * @param  object BaseModel $playlistItemVote Vote item
	 */
	public function onStore( $parameters )
	{
		$playlistItemVote = $parameters['item'];

		$playlistItem = PlaylistItem::find($playlistItemVote->playlist_item_id);

		// Skip the playlist item if we have met or exceeded the downvote threshold
		$activeSessions = DB::table('sessions')->where('last_activity', '>', time()-600)->count();
		$votesRequired = ($playlistItem->playlist->user_skip_threshold)* 0.01 * $activeSessions;

		$playlistItemVotes = abs($playlistItem->playlistItemVotes()->count());

		if( ($playlistItemVotes + 1) >= $votesRequired ) // if this vote tips the playlist item beyond the threshold
		{
			// skip the item
			$playlistItem->playback_state = 2;
			$playlistItem->skip_reason = ($playlistItemVotes + 1) . ' users voted to skip';
			$playlistItem->save();
			Log::debug('Skipping playlist item ' . $playlistItem->id . ' as ' . $playlistItemVotes . ' user(s) voted to skip', ['item' => $playlistItem]);
		}
	}

}
