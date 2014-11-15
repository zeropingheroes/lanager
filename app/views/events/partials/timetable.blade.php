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
			eventColor: "#0f6c00",
			events: "{{ URL::route('events.timetable') }}",
		});
	});
</script>
<div id="timetable"></div>
