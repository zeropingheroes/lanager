@if(count($events))
	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Status</th>
				<th>Time</th>
				<th colspan="2">Signups</th>
				<th>Type</th>
				@if( Authority::can('manage', 'events') )
					<th class="text-center">{{ Icon::cog() }}</th>
				@endif
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
					@if( $event->event_type_id )
						{{ $event->type->present()->colouredType }}
					@endif
				</td>
				@if( Authority::can('manage', 'events') )
					<td class="text-center">
						@include('buttons.edit', ['resource' => 'events', 'item' => $event, 'size' => 'extraSmall'])
						@include('buttons.destroy', ['resource' => 'events', 'item' => $event, 'size' => 'extraSmall'])
					</td>
				@endif
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	<p>No events found!</p>
@endif