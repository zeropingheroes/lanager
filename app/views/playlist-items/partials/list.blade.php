@if( $items->count() )
	<table class="table">
		<thead>
			<tr>
				<th>Submitter</th>
				<th>Title</th>
				<th>Duration</th>
				<th>Submitted</th>
				@if( Authority::can('create', 'playlists.items.votes') OR
					Authority::can('manage', 'playlists.items') )
					<th class="text-center">{{ Icon::cog() }}</th>
				@endif
			</tr>
		</thead>
		<tbody>
		@foreach( $items as $item )
			<tr>
				<td>
					@include('users.partials.avatar-username', ['user' => $item->user])
				</td>
				<td>
					{{{ $item->title }}}
				</td>
				<td>
					{{ Duration::shortFormat($item->duration) }}
				</td>
				<td>
					{{ $item->created_at->diffForHumans() }}
				</td>
				<td>
					@include('playlist-items.partials.vote-skip', ['item' => $item])
					@include('playlist-items.partials.skip', ['item' => $item])
					@include('playlist-items.partials.destroy', ['item' => $item])
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	No playlist entries to show!
@endif
