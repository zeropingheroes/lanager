@extends('layouts.default')
@section('content')
	@include('layouts.default.title')
	@include('layouts.default.alerts')
	@if(count($events))
		<table class="table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Time</th>
					<th>Status</th>
					<th>Type</th>
				</tr>
			</thead>
			<tbody>
			@foreach( $events as $event )
				<tr>
					<td>{{ link_to_route('events.show', $event->name, $event->id) }}</td>
					<td>{{ $event->present()->timespan }}</td>
					<td>{{ $event->present()->timespanStatusLabel }}</td>
					<td>{{ (isset($event->type->name) ? $event->type->name : '') }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		No events found!
	@endif
@endsection
