@extends('layouts.fullscreen')
@section('content')

<script type="text/javascript">

	var dashboard = {

		clock: {
			update: function()
			{
				$('#clock').text( moment().format("h:mma") );
			},
		},

		events: {
			feedUrl: '{{ url() }}/api/events/',
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

						if ( event.start.isBefore( now ) && event.end.isAfter( now ) )
						{
							event.status = '<span class="label label-success">Now</span>';
							event.timer = 'Ending ' + event.end.fromNow();

						}
						else if ( event.start.isAfter( now ) && event.end.isAfter( now ))
						{
							event.status = '<span class="label label-info">Next</span>';
							event.timer = 'Starting ' + event.start.fromNow();
							if ( futureEventCount == 1 ) return true; // only display 1 future event
							futureEventCount++;
						}
						else
						{
							return true; // skip event as it is in the past
						}
						// add a row for the event
						tbody +=
						'<tr>' + 
							'<td class="event-status">' + event.status + '</td>' +
							'<td class="event-name"><a href="' + event.links[0].uri + '">' + event.name + '</a></td>' +
							'<td class="event-type">' + event.type.name + '</td>' +
							'<td class="event-timer">' + event.timer + '</td>' +
						'</tr>';

					});
					if ( tbody === '' )
					{
						tbody = '<tr><td class="text-muted">No events to show!</td></tr>';
					}
					$("#events tbody").html( tbody );
				});
			},
		},
		applicationUsage: {
			feedUrl: '{{ url() }}/api/application-usage/',
			poll: function()
			{
				console.log('Polling application usage');
				$.getJSON( dashboard.applicationUsage.feedUrl, function( applicationUsage )
				{
					if ( applicationUsage.length != 0 )
					{
						var tbody = '';
						$.each(applicationUsage, function(i, applicationInUse)
						{
							applicationInUse.userList = '';
							$.each(applicationInUse.users, function(i, user) {
								applicationInUse.userList += '<img src="' + user.avatar_small + '">';
							});
							tbody +=
							'<tr>' + 
								'<td class="application-logo"><img src="' + applicationInUse.logo_small + '"></td>' +
								'<td class="application-name"><a href="' + applicationInUse.url + '">' + applicationInUse.name + '</a></td>' +
								'<td class="application-user-count">' + applicationInUse.users.length + ' In Game</td>' +
								'<td class="application-user-list">' + applicationInUse.userList + '</td>' +
							'</tr>';

						});
						$("#applicationUsage tbody").html(tbody);
					}
					else
					{
						$("#applicationUsage tbody").html('<tr><td class="text-muted">No games being played!</td></tr>');
					}
				});
			},
		},
		shouts: {
			feedUrl: '{{ url() }}/api/shouts/',
			poll: function()
			{
				console.log('Polling shouts');
				$.getJSON( dashboard.shouts.feedUrl, function( shouts )
				{
					if ( shouts.data.length != 0 )
					{
						var tbody = $('<tbody>');

						$.each(shouts.data, function(idx, shout) {
							if ( shout.pinned )
							{
								var row = $('<tr class="bg-info shout-pinned">');
							}
							else
							{
								var row = $('<tr>');
							}

							row.append(
								$('<td>').append(
									$('<img>').attr('src', shout.user.avatar_medium)
								).addClass('shout-user-avatar shrink')
							);
							row.append(
								$('<td>').append(
									$('<a>').attr('href', shout.user.links[0].uri
									).text(shout.user.username))
								.addClass('shout-user-username shrink')
							);
							row.append(
								$('<td>').text(shout.content)
								.addClass('shout-content expand')
							);

							tbody.append(row);
						});
						$('#shouts').html(tbody);
					}
					else
					{
						$('#shouts tbody').html('<tr><td class="text-muted">No shouts to show!</td></tr>');
					}
				});
			},
		},
	};
	$( document ).ready(function() {
		dashboard.clock.update();
		dashboard.events.poll();
		dashboard.applicationUsage.poll();
		dashboard.shouts.poll();
		window.setInterval(dashboard.clock.update,1000 * 1);
		window.setInterval(dashboard.events.poll,1000 * 30);
		window.setInterval(dashboard.applicationUsage.poll,1000 * 15);
		window.setInterval(dashboard.shouts.poll,1000 * 15);
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

<table class="table table-valign-middle" id="events">
	<tbody>
		<tr>
			<td>
				{{ ProgressBar::normal(100)->animated() }}
			</td>
		</tr>
	</tbody>
</table>


<div id="carousel" class="carousel slide" data-ride="carousel" data-interval="15000">
	<div class="carousel-inner" role="listbox">
		<div class="item active">
			<h1>Games</h1>
			<table class="table table-valign-middle" id="applicationUsage">
				<tbody>
					<tr>
						<td>
							{{ ProgressBar::normal(100)->animated() }}
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="item">
			<h1>Shouts</h1>
			<table class="table table-valign-middle" id="shouts">
				<tbody>
					<tr>
						<td>
							{{ ProgressBar::normal(100)->animated() }}
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection
