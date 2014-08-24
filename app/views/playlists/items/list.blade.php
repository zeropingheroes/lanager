@if(count($items))
	{{ Table::open() }}
	<?php
	foreach( $items as $item )
	{

		$user = $item->user;
		$controls = '';

		switch($item->playback_state)
		{
			case 2:
				$itemStateLabel = Label::warning('Skipped - '.e($item->skip_reason));
				break;
			default:
				$itemStateLabel = '';
		}

		if( $history == true )
		{
			$tableBody[] = array(
				'submitter'		=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.View::make('users.partials.avatar', ['user' => $user]).' '.e($user->username).'</a>',
				'title'			=> link_to($item->url, $item->title, array('target'=>'_blank')),
				'state'			=> $itemStateLabel,
				'duration'		=> Duration::shortFormat($item->duration),
				'played'		=> $item->played_at->diffForHumans(),
			);
		}
		else
		{
			if( Authority::can('create', 'playlist.item.votes') )
			{
				if( $item->id == $nowPlaying->id )
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

			if( Authority::can('manage', 'playlist.items') )
			{
				$adminControls = Form::open(
							array(
								'route' => array(
									'playlists.items.update',
										'playlist' => $playlist->id,
										'item' => $item->id
								),
								'method' => 'PUT',
								'class' => 'form-inline',
								'id' => 'skip'.$item->id)
							);
				$adminControls .= Form::hidden('playback_state', 2);
				$adminControls .= Button::xs_danger_submit('', array('title' => 'Immediately skip this item', 'name' => 'Submit' ))->with_icon('fast-forward');
				$adminControls .= Form::close();
				$adminControls .= '<script type="text/javascript">
									$( "#'.'skip'.$item->id.'" ).submit(function( event ) {
										var reason = prompt("Please enter a brief reason for skipping this item");
										var input = $("<input>")
										.attr("type", "hidden")
										.attr("name", "skip_reason").val(reason);
										$( "#'.'skip'.$item->id.'").append($(input));
								});</script>';
			}
			else
			{
				$adminControls = '';
			}

			$tableBody[] = array(
				'submitter'		=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.View::make('users.partials.avatar', ['user' => $user]).' '.e($user->username).'</a>',
				'title'			=> e($item->title),
				'duration'		=> Duration::shortFormat($item->duration),
				'submitted'		=> $item->created_at->diffForHumans(),
				'controls'		=> $adminControls.$controls,
			);
		}
	}

	?>
	{{ Table::body($tableBody) }}
	{{ Table::close() }}
	@if( $history )
		{{ $items->appends(array('history' => '1'))->links() }}
	@else
		{{ $items->links() }}
	@endif
	<span class="pull-right playlist-total-time">Total: {{ Duration::shortFormat($duration) }}</span>
@else
	No playlist entries to show!
@endif