@if (!empty($eventTypes))

	<table class="table">
		<thead>
			<tr>
				<th>Name</th>
				<th>Colour</th>
				<th>Events</th>
				@if ( Authority::can('manage', 'event-types') )
					<th class="text-center">{{ Icon::cog() }}</th>
				@endif
			</tr>
		</thead>
		<tbody>
		@foreach( $eventTypes as $eventType )
			<tr>
				<td>{{ $eventType->name }}</td>
				<td>
					@if ( ! empty( $eventType->colour ))
						<span style="color: {{ $eventType->colour }}" title="{{ $eventType->colour }}">{{ Icon::calendar() }}</span>
					@endif
				</td>
				<td>
					@include('plural', ['singular' => 'event', 'collection' => $eventType->events ])
				</td>
				@if ( Authority::can('manage', 'event-types') )
					<td class="text-center">
						@include('buttons.edit', ['resource' => 'event-types', 'item' => $eventType, 'size' => 'extraSmall'])
						@include('buttons.destroy', ['resource' => 'event-types', 'item' => $eventType, 'size' => 'extraSmall'])
					</td>
				@endif
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	<p>No event types to show!</p>
@endif
