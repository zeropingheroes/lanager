@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@if(count($playlists))
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Status</th>
					@if( Authority::can('manage', 'playlists') )
						<th class="text-center">{{ Icon::cog() }}</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@foreach( $playlists as $playlist )
				<tr>
					<td>{{ link_to_route('playlists.items.index', $playlist->name, $playlist->id) }}</td>
					<td>{{ $playlist->description }}</td>
					<td>@include('playlists.partials.status', ['playlist' => $playlist])</td>
					@if( Authority::can('manage', 'playlists') )
						<td class="text-center">
							@include('buttons.edit', ['resource' => 'playlists', 'item' => $playlist, 'size' => 'extraSmall'])
							@include('buttons.destroy', ['resource' => 'playlists', 'item' => $playlist, 'size' => 'extraSmall'])
							@include('playlists.partials.buttons.play-pause', ['playlist' => $playlist])
							@include('playlists.partials.buttons.fullscreen', ['playlist' => $playlist])
						</td>
					@endif
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		No playlists found!
	@endif

	@include('buttons.create', ['resource' => 'playlists'])

@endsection
