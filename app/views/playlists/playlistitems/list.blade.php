@if(count($playlistItems))
	{{ Table::open() }}
	<?php
	foreach( $playlistItems as $playlistItem )
	{
		$itemDuration = new Zeropingheroes\Lanager\Helpers\Duration($playlistItem->duration);
		$playlistDurationLabel = new Zeropingheroes\Lanager\Helpers\Duration($playlistDuration);
		$submitted = new ExpressiveDate($playlistItem->created_at);
		$played = new ExpressiveDate($playlistItem->updated_at);

		$user = $playlistItem->user;

		switch($playlistItem->playback_state)
		{
			case 2:
				$itemStateLabel = Label::warning('Skipped - '.$playlistItem->skip_reason);
				break;
			default:
				$itemStateLabel = '';
		}

		if( isset($history) )
		{
			$tableBody[] = array(
				'submitter'		=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
				'title'			=> link_to($playlistItem->url, $playlistItem->title, array('target'=>'_blank')),
				'state'			=> $itemStateLabel,
				'duration'		=> $itemDuration->shortFormat(),
				'played'		=> $played->getRelativeDate(),
			);
		}
		else
		{
			$tableBody[] = array(
				'submitter'		=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
				'title'			=> e($playlistItem->title),
				//'controls'		=> Button::xs_link(URL::route('playlists.playlistitems.skip', array('playlist' => $playlist->id, 'playlistItem' => $playlistItem->id), '', array('title' => 'Vote to skip this item')))->with_icon('step-forward'),
				'duration'		=> $itemDuration->shortFormat(),
				'submitted'		=> $submitted->getRelativeDate(),
			);
		}
	}

	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
	{{ $playlistItems->links() }}
	<span class="pull-right playlist-total-time">Total: {{ $playlistDurationLabel->shortFormat() }}</span>
@else
	No playlist entries to show!
@endif