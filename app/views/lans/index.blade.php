@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')

	@if (count($lans))
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Dates</th>
					<th>Times</th>
					<th>Duration</th>
					<th>Achievements</th>
					@if ( Authority::can('manage', 'lans') )
						<th class="text-center">{{ Icon::cog() }}</th>
					@endif
				</tr>
			</thead>
			<tbody>
			@foreach( $lans as $lan )
				<tr>
					<td>{{ link_to_route('lans.show', $lan->name, $lan->id) }}</td>
					<td>{{ $lan->present()->monthYear }}</td>
					<td>{{ $lan->present()->timespan }}</td>
					<td>{{ $lan->present()->duration }}</td>
					<td>
						@include('plural', ['singular' => 'award', 'collection' => $lan->userAchievements])
					</td>
					@if ( Authority::can('manage', 'lans') )
						<td class="text-center">
							@include('buttons.edit', ['resource' => 'lans', 'item' => $lan, 'size' => 'extraSmall'])
							@include('buttons.destroy', ['resource' => 'lans', 'item' => $lan, 'size' => 'extraSmall'])
						</td>
					@endif
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		<p>No LANs found!</p>
	@endif

	@include('buttons.create', ['resource' => 'lans'])

@endsection
