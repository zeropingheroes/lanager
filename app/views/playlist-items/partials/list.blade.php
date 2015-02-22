@if(count($playlistItems))
	<table class="table">
		<tbody>
		@foreach( $playlistItems as $playlistItem )
			<tr>
				<td>
					@include('users.partials.avatar-username', ['user' => $playlistItem->user])
				</td>
				<td>
					{{ $playlistItem->title }}
				</td>
				<td>
					{{ Duration::shortFormat($playlistItem->duration) }}
				</td>
				<td>
					{{ $playlistItem->created_at->diffForHumans() }}
				</td>
				<td>
					@if( Authority::can('create', 'playlists.items.votes'))
						@include('playlist-items.partials.vote-skip', ['item' => $playlistItem])
					@endif
					@if( Authority::can('delete', 'playlists.items', $playlistItem->id))
						@include('playlist-items.partials.destroy', ['item' => $playlistItem])
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	No playlist entries to show!
@endif
