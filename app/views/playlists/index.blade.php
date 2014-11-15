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
				</tr>
			</thead>
			<tbody>
			@foreach( $playlists as $playlist )
				<tr>
					<td>{{ link_to_route('playlists.items.index', $playlist->name, $playlist->id) }}</td>
					<td>{{ $playlist->description }}</td>
					<td>{{ $playlist->playback_state }}</td> {{-- todo: make presenter --}}
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		No playlists found!
	@endif
@endsection
