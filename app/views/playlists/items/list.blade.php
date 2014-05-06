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
		$controls = '';

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
			if( Authority::can('create', 'playlist.item.votes') )
			{
				if( Config::get('lanager/playlist.downvoteFutureItems') OR $item->id == $nowPlaying->id )
				{
					if( Auth::user()->votes()->where('playlist_item_id', $item->id)->count() == 0 )
					{
						$controls = Form::open(
							array(
								'route' => array(
									'playlists.items.votes.store',
										'playlist' => $playlist->id,
										'item' => $item->id
									),
								'method' => 'POST',
								'class' => 'form-inline')
							);
						$controls .= Button::xs_submit('', array('title' => 'Vote to skip this item', 'name' => 'vote'))->with_icon('step-forward');
						$controls .= Form::close();
					}
					else
					{
						$controls = Form::open(
							array(
								'route' => array(
									'playlists.items.votes.destroy',
										'playlist' => $playlist->id,
										'item' => $item->id,
										'vote' => Auth::user()->votes()->where('playlist_item_id', $item->id)->first()->id,
									),
								'method' => 'DELETE',
								'class' => 'form-inline')
							);
						$controls .= Button::xs_danger_submit('', array('title' => 'Remove your vote to skip this item', 'name' => 'vote'))->with_icon('step-forward');
						$controls .= Form::close();
					}
				}
			}
			$tableBody[] = array(
				'submitter'		=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.HTML::userAvatar($user).' '.e($user->username).'</a>',
				'title'			=> e($item->title),
				'duration'		=> $itemDuration->shortFormat(),
				'submitted'		=> $submitted->getRelativeDate(),
				'controls'		=> $controls,
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