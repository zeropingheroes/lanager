@if(count($playlistItems))
	{{ Table::open() }}
	<?php
	foreach( $playlistItems as $playlistItem )
	{
		$controls = '';
		$playbackStateLabel = '';

		$user = $playlistItem->user;
		$duration = new Zeropingheroes\Lanager\Helpers\Duration($playlistItem->duration);
		$submitted = new ExpressiveDate($playlistItem->created_at);

		// Add playing/paused label to top item in playlist
		if( !isset($tableBody) )
		{
			if ($playlist->playback_state == 1)
			{
				$playbackStateLabel = Label::success('Playing');
			}
			elseif( $playlist->playback_state == 0 )
			{
				$playbackStateLabel = Label::info('Paused');
			}
		}

		$tableBody[] = array(
			'user'			=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
			'title'			=> e($playlistItem->title),
			'state'			=> $playbackStateLabel,
			'duration'		=> $duration->shortFormat(),
			'submitted'		=> $submitted->getRelativeDate(),
		);
	}

	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
	{{ $playlistItems->links() }}
@else
	No playlist entries to show!
@endif