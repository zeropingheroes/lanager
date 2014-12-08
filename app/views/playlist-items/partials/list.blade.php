@if(count($playlistItems))

	<?php
	foreach( $playlistItems as $playlistItem )
	{
		$user = $playlistItem->user;

		$tableBody[] = array(
			'submitter'		=> View::make('users.partials.avatar-username', ['user' => $user]),
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