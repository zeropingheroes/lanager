@if(count($playlistItems))

	<?php
	foreach( $playlistItems as $playlistItem )
	{
		$user = $playlistItem->user;

		$tableBody[] = array(
			'submitter'		=> '<a class="pull-left" href="'.URL::route('users.show', $user->id).'">'.View::make('users.partials.avatar', ['user' => $user]).' '.e($user->username).'</a>',
			'title'			=> e($playlistItem->title),
			'duration'		=> Duration::shortFormat($playlistItem->duration),
			'submitted'		=> $playlistItem->created_at->diffForHumans(),
		);
	}
	?>
	{{ Table::withContents($tableBody) }}
@else
	No playlist entries to show!
@endif