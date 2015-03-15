@if( $items->count() )
	<table class="table">
		<thead>
			<tr>
				<th>Submitter</th>
				<th>Title</th>
				<th>Duration</th>
				<th>Skipped</th>
				<th>Reason</th>
				@if( Authority::can('manage', 'playlists.items') )
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
					{{ $item->updated_at->diffForHumans() }}
				</td>
				<td>
					{{{ $item->skip_reason }}}
				</td>
				@if( Authority::can('manage', 'playlists.items') )
					<td>
						@include('playlist-items.partials.destroy', ['item' => $item])
					</td>
				@endif
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	<p>No playlist entries to show!</p>
@endif
