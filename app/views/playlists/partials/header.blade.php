@if( $playlist->count() )
	<div class="row">
		<div class="col-md-8">
			<h1 class="pull-left">
				Playlist: {{{ $playlist->name }}}
				<small>
					@include('playlists.partials.buttons.play-pause', ['playlist' => $playlist])
					@include('playlists.partials.buttons.fullscreen', ['playlist' => $playlist])
				</small>
			</h1>
		</div>
		<div class="col-md-4">
			<h1 class="pull-right">
				@include('playlists.partials.status', ['playlist' => $playlist] )
			</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<h4>{{{ $playlist->description }}}</h4>
		</div>
	</div>
@endif