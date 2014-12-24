function setTimeline(view) {
	var parentDiv = $('.fc-slats:visible').parent();
	var timeline = parentDiv.children(".timeline");
	if (timeline.length == 0) { //if timeline isn't there, add it
		timeline = $("<hr>").addClass("timeline");
		parentDiv.prepend(timeline);
	}

	var curTime = new Date();

	var curCalView = $("#timetable").fullCalendar('getView');
	if (curCalView.intervalStart < curTime && curCalView.intervalEnd > curTime) {
		timeline.show();
	} else {
		timeline.hide();
		return;
	}
	var calMinTimeInMinutes = strTimeToMinutes(curCalView.opt("minTime"));
	var calMaxTimeInMinutes = strTimeToMinutes(curCalView.opt("maxTime"));
	var curSeconds = (( ((curTime.getHours() * 60) + curTime.getMinutes()) - calMinTimeInMinutes) * 60) + curTime.getSeconds();
	var percentOfDay = curSeconds / ((calMaxTimeInMinutes - calMinTimeInMinutes) * 60);

	var topLoc = Math.floor(parentDiv.height() * percentOfDay);
	timeline.css({top: topLoc + "px"});

	if (curCalView.name == "agendaWeek") { //week view, don't want the timeline to go the whole way across
		var dayCol = $(".fc-today:visible");
		var left = dayCol.position().left + 1;
		var width = dayCol.width() + 1;
		timeline.css({left: left + "px", width: width + "px"});
	}
}

function strTimeToMinutes(str_time) {
	var arr_time = str_time.split(":");
	var hour = parseInt(arr_time[0]);
	var minutes = parseInt(arr_time[1]);
	return((hour * 60) + minutes);
}