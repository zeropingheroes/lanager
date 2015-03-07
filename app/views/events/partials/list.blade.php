@if(count($events))
	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Status</th>
				<th>Time</th>
				<th colspan="2">Signups</th>
				<th>Type</th>
			</tr>
		</thead>
		<tbody>
		@foreach( $events as $event )
			<tr>
				<td>
					{{ link_to_route('events.show', $event->name, $event->id) }}
				</td>
				<td>
					{{ $event->present()->timespanStatusLabel }}
				</td>
				<td>
					{{ $event->present()->timespan }}
				</td>
				<td>
					{{ $event->present()->signupTimespanStatusLabel }}
				</td>
				<td>
					@include('plural', ['singular' => 'signup', 'collection' => $event->eventSignups ])
				</td>
				<td>
					{{ (isset($event->type->name) ? $event->type->name : '') }}
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	No events found!
@endif