$(function() {
	// page is now ready, initialize the calendar...
	$('#calendar').fullCalendar({
		// put your options and callbacks here
		displayEventTime: false,
		showNonCurrentDates: true,
		fixedWeekCount: false,
		events: function(start, end, timezone, callback) {
			var verbose = true;	//	false;
			// DONE: calendarUrl no more hardcoded :)
			var cfgFile = $('#icsfile').html();	// but defined in invisible Paragraph - not elegant :-/
			if (verbose)	console.log('yaml CFG File:' + cfgFile);
			calendarUrl = getAbsolutePath() + 'user/data/calendars/' + cfgFile;
			if (verbose)	console.log('Calendar URL:' + calendarUrl);
			$.get(calendarUrl, function (data) {
				var iCalendarData = [];
				var lines = data.split("\n");
				for (var i = 0, len = lines.length; i < len; i++) {
					if ($.trim(lines[i]).length > 0)    {
						if (verbose)	console.log(lines[i]);
						iCalendarData.push($.trim(lines[i]));
					}
				}
				iCalendarData = iCalendarData.join("\r\n");
				var jcalData = ICAL.parse(iCalendarData);
				var comp = new ICAL.Component(jcalData);
				var eventComps = comp.getAllSubcomponents("vevent");
				// map them to FullCalendar events
				var events = [];
				events = $.map(eventComps, function (item) {
					if (item.getFirstPropertyValue("class") == "PRIVATE") {
						return null;
					}
					else {
						return {
							"title": item.getFirstPropertyValue("summary"), // + ";",
							"start": item.getFirstPropertyValue("dtstart").toJSDate(),
							"url": item.getFirstPropertyValue("url"),
							"end": item.getFirstPropertyValue("dtend").toJSDate(),
							"location": item.getFirstPropertyValue("location")
						};
					}
				})
				if (verbose)	console.log(events)
				callback(events);
			}, 'text')
		}
	})
})  

function getAbsolutePath() {	// see https://www.sitepoint.com/jquery-current-page-url/
	var loc = window.location;
	var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
	return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

