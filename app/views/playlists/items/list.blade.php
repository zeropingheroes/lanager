@if(count($playlistItems))
	{{ Table::open() }}
	<?php
	foreach( $playlistItems as $playlistItem )
	{
		$controls = '';
		$playbackStateLabel = '';

		$user = $playlistItem->user;
		$duration = new Zeropingheroes\LanagerCore\Helpers\Duration($playlistItem->duration);
		$submitted = new ExpressiveDate($playlistItem->created_at);

		// Change labels and controls for different playback states
		switch($playlistItem->playback_state)
		{
			case 0: // unplayed
				if( Authority::can( 'manage', 'playlist', $playlist->id ) )
				{
					//$controls = Button::xs_normal('', array('title' => 'Skip this item'))->with_icon('step-forward');
				}
				break;
			case 1: // playing
				$playbackStateLabel = Label::success('Now Playing');
				if( Authority::can( 'manage', 'playlist', $playlist->id ) )
				{
					$controls = Button::link_xs_normal(
						route('playlist.item.pause', array('playlist' => $playlist->id, 'playlistItem' => $playlistItem->id)),
						'', array('title' => 'Pause this item')
						)->with_icon('pause');

					//$controls .= Button::xs_normal('', array('title' => 'Skip this item'))->with_icon('step-forward');
				}
				else
				{
					//$controls = Button::xs_normal('', array('title' => 'Vote to skip this item'))->with_icon('step-forward');
				}
				break;
			case 2: // paused
				$playbackStateLabel = Label::info('Paused');
				if( Authority::can( 'manage', 'playlist', $playlist->id ) )
				{
					$controls = Button::link_xs_normal(
						route('playlist.item.pause', array('playlist' => $playlist->id, 'playlistItem' => $playlistItem->id)),
						'', array('title' => 'Unpause this item')
						)->with_icon('play');
				}
				break;
			case 3: // skipped
				$playbackStateLabel = Label::info('Skipped');
				break;

		}

		$tableBody[] = array(
			'user'			=> '<a class="pull-left" href="'.URL::route('user.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
			'title'			=> e($playlistItem->title),
			'state'			=> $playbackStateLabel,
			'controls'		=> $controls,
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