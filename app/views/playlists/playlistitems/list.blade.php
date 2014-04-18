@if(count($playlistItems))
	{{ Table::open() }}
	<?php
	foreach( $playlistItems as $playlistItem )
	{
		$duration = new Zeropingheroes\Lanager\Helpers\Duration($playlistItem->duration);
		$submitted = new ExpressiveDate($playlistItem->created_at);
		$played = new ExpressiveDate($playlistItem->updated_at);

		$user = $playlistItem->user;

		switch($playlistItem->playback_state)
		{
			case 2:
				$itemStateLabel = Label::warning('Skipped');
				break;
			default:
				$itemStateLabel = '';
		}

		if( isset($history) )
		{
			$title = link_to($playlistItem->url, $playlistItem->title, array('target'=>'_blank'));
			$timestamp = $played->getRelativeDate();
		}
		else
		{
			$title = e($playlistItem->title);
			$timestamp = $submitted->getRelativeDate();
		}

		$tableBody[] = array(
			'user'			=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
			'title'			=> $title,
			'state'			=> $itemStateLabel,
			'duration'		=> $duration->shortFormat(),
			'timestamp'		=> $timestamp,
		);
	}

	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
	{{ $playlistItems->links() }}
@else
	No playlist entries to show!
@endif