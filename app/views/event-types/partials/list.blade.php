@if(!empty($eventTypes))
	@foreach($eventTypes as $eventType)
		<li>{{ link_to_route('event-types.edit',$eventType->name, $eventType->id) }}</li>
	@endforeach
@endif
