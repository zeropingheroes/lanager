@extends('lanager-core::layouts.default')
@section('content')

<h2>{{ $title }}</h2>

<script>
	$(document).ready(function() {
		$('#timetable').fullCalendar({
			header: {
				left: '',
				center: '',
				right: ' agendaDay agendaWeek today prev,next'
			},
			viewDisplay: function(view) {
				try {
					setTimeline();
				} catch(err) {}
			},
			slotEventOverlap: false,
			editable: false,
			allDaySlot: false,
			defaultView: 'agendaDay',
			firstDay: 1,
			theme: false,
			height: 1500,
			eventColor: "{{ Config::get('lanager-core::defaultEventColour') }}",
			events: "{{ URL::route('event.timetable') }}",
		});
	});
</script>

<div id="timetable"></div>
<br>
<br>
Having trouble viewing the timetable? Try the {{ link_to_route('event.index', 'Events List') }}
@endsection