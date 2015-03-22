@extends('layouts.fullscreen')
@section('content')

<script type="text/javascript">

	var dashboard = {
		
		events: {
			feedUrl: '{{ url() }}/api/events/?orderBy=start',
			poll: function()
			{
				console.log('Polling events');
				$.getJSON( dashboard.events.feedUrl, function( events )
				{
					var now = moment();
					var tbody = '';
					var futureEventCount = 0;
					var show = { id : true, name : true, type: true };
					$.each(events.data, function(i, event)
					{
						event.start = moment(event.start);
						event.end = moment(event.end);

						if( event.start.isBefore( now ) && event.end.isAfter( now ) )
						{
							event.status = '<span class="label label-success">Now</span>';
							event.timer = 'Ending ' + event.end.fromNow();

						}
						else if( event.start.isAfter( now ) && event.end.isAfter( now ))
						{
							event.status = '<span class="label label-info">Next</span>';
							event.timer = 'Starting ' + event.start.fromNow();
							if( futureEventCount == 1 ) return true; // only display 1 future event
							futureEventCount++;
						}
						else
						{
							return true; // skip this event
						}
						tbody +=
						'<tr>' + 
							'<td class="event-status">' + event.status + '</td>' +
							'<td class="event-name">' + event.name + '</td>' +
							'<td class="event-type">' + event.type.name + '</td>' +
							'<td class="event-timer">' + event.timer + '</td>' +
						'</tr>';

					});
					$("#events tbody").html(tbody);
				});
			},
		},

		clock: {
			update: function()
			{
				$('#clock').text( moment().format("h:mma") );
			},
		},

	};
	$( document ).ready(function() {
		dashboard.clock.update();
		dashboard.events.poll();
		window.setInterval(dashboard.events.poll,1000 * 30);
		window.setInterval(dashboard.clock.update,1000 * 1);
	});

</script>

<div class="row">
	<div class="col-md-8">
		<h1 class="pull-left">Events</h1>
	</div>
	<div class="col-md-4">
		<h1 class="pull-right" id="clock">

		</h1>
	</div>
</div>

<table class="table" id="events">
	<tbody>
		<tr>
			<td>
				{{ ProgressBar::normal(100)->animated() }}
			</td>
		</tr>
	</tbody>
</table>

@endsection
