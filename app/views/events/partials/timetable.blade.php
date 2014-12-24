<script>
	$(document).ready(function() {
		var fullCalendarEvents = [];
		var timelineInterval;

		$.ajax({
			dataType: "json",
			url: "http://" + location.host + "/api/events", // todo: fix URL generator route issue
			success: function(response) {
				// translate data into format that fullCalendar can use
				$.each(response.data, function( index, value ) {
					var event = {
						title: 	value.name,
						start: 	value.start,
						end: 	value.end,
						color: 	value.type.colour,
						url: 	value.links[0].uri
					};
					fullCalendarEvents.push(event);
				});
				// add events to calendar
				$('#timetable').fullCalendar('addEventSource', fullCalendarEvents);
			}
		});

		$('#timetable').fullCalendar({
			header: {
				left: '',
				center: '',
				right: ' agendaDay agendaWeek today prev,next'
			},
			viewRender: function(view) {              
				if(typeof(timelineInterval) != 'undefined'){
					window.clearInterval(timelineInterval); 
				}
					timelineInterval = window.setInterval(setTimeline, 300000);
				try {
					setTimeline();
				} catch(err) {}
			},
			slotEventOverlap: false,
			editable: false,
			allDaySlot: false, // todo: implement all day events in database
			defaultView: 'agendaDay',
			firstDay: 1,
			theme: false,
			height: "auto",
			eventColor: "#0f6c00",
		});
	});
</script>
<div id="timetable"></div>
