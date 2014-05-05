@if(count($items))
	{{ Table::open() }}
	<?php
	foreach( $items as $item )
	{
		$itemDuration = new Zeropingheroes\Lanager\Helpers\Duration($item->duration);
		$playlistDurationLabel = new Zeropingheroes\Lanager\Helpers\Duration($duration);
		$submitted = new ExpressiveDate($item->created_at);
		$played = new ExpressiveDate($item->updated_at);

		$user = $item->user;

		switch($item->playback_state)
		{
			case 2:
				$itemStateLabel = Label::warning('Skipped - '.$item->skip_reason);
				break;
			default:
				$itemStateLabel = '';
		}

		if( $history == true )
		{
			$tableBody[] = array(
				'submitter'		=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
				'title'			=> link_to($item->url, $item->title, array('target'=>'_blank')),
				'state'			=> $itemStateLabel,
				'duration'		=> $itemDuration->shortFormat(),
				'played'		=> $played->getRelativeDate(),
			);
		}
		else
		{
			$tableBody[] = array(
				'submitter'		=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
				'title'			=> e($item->title),
				//'controls'		=> Button::xs_link(URL::route('playlists.items.skip', array('playlist' => $playlist->id, 'item' => $item->id), '', array('title' => 'Vote to skip this item')))->with_icon('step-forward'),
				'duration'		=> $itemDuration->shortFormat(),
				'submitted'		=> $submitted->getRelativeDate(),
			);
		}
	}

	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
	{{ $items->links() }}
	<span class="pull-right playlist-total-time">Total: {{ $playlistDurationLabel->shortFormat() }}</span>
@else
	No playlist entries to show!
@endif