@extends('layouts.default')
@section('content')
	<?php
		if( ! isset($history) )
		{
			if( $playlist->playback_state == 1)	
			{
				$playlistState = ' <span class="playlist-playback-state">' . Label::success('Playing') . '</span>';
			}
			elseif( $playlist->playback_state == 0 )
			{
				$playlistState = ' <span class="playlist-playback-state">' . Label::info('Paused') . '</span>';
			}
			$playlistState = '';
		}
		else
		{
			$playlistState = '';
		}
	?>

	<div class="row">
		<div class="col-md-6">
			<h2>{{{ $title }}}{{ $playlistState }}</h2>
		</div>
		@if( Authority::can('manage', 'playlists') AND !isset($history) )
			<div class="col-md-6">
				<div class="pull-right playlist-controls">@include('playlists.controls')</div>
			</div>
		@endif
	</div>
	@include('playlists.playlistitems.form')
	@include('playlists.playlistitems.list')

@endsection