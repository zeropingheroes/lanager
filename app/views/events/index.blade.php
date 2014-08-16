@extends('layouts.default')
@section('content')
	@if(count($events))
		{{ Table::open(array('class' => 'events-list')) }}
		{{ Table::headers('Name', 'Time', '', 'Type', 'Signups', '') }}

		<?php
		foreach( $events as $event )
		{
			$tableBody[] = array(
				'name'					=> link_to_route('events.show', $event->name, $event->id),
				'time'					=> $event->present()->timespan,
				'event-timespan-status'	=> $event->present()->timespanStatusLabel,
				'type'					=> (isset($event->type->name) ? $event->type->name : ''),
				'signup-count'			=> count($event->users).' '.str_plural('user',count($event->users)),
				'signup-timespan-status'=> $event->present()->signupTimespanStatusLabel,
			);
		}
		?>
		{{ Table::body($tableBody) }}
		{{ Table::close() }}
	@else
		No events found!
	@endif
@endsection
