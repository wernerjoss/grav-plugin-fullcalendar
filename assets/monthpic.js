/*	new approach for update month picture upon next - prev - home click
	evaluate Text in Month div fc-center: this is the Month name in current locale
	build pic name from that.
	pic files must be named like months in current locale !
*/

$(document).ready(function() {
	var debug = false;
	var lastmonthname = null;

	if($('#actMonth').length){	// do not setup image load/change when div actMonth does not exist
		setInterval(function() {    // statt setTimeout() - siehe https://www.w3schools.com/jsref/met_win_settimeout.asp
			function fileExists(filename) {
				var response = $.ajax({
					url: filename,
					type: 'HEAD',
					async: false	// does not work with true :-/
				}).status;
				if (debug)	console.log('response:',response);
				return (response != "200") ? false : true;
			}
			var activemonth = $('div.fc-center h2').text();	// fc 4.3
			if (debug) console.log('activemonth:', activemonth);
			var monthname = activemonth.split(" ")[0];

			if (monthname.length > 0)  {
				var path = document.location.pathname;
				var monthpic = path + '/' + monthname + '.jpg';
				if (debug) console.log('monthpic:', monthpic);
				if (monthname != lastmonthname) {
					if (debug) console.log('monthpic:', monthpic);
					if (fileExists(monthpic))   {   // 25.08.20 see https://github.com/wernerjoss/grav-plugin-fullcalendar/issues/20
						$.ajax({
							type: "HEAD",
							async: true,
							url: window.location + '/' + monthname + '.jpg'
						}).done(function() {
							if (debug) console.log("monthpic found");
							if (document.getElementById('actMonth') != null) document.getElementById('actMonth').innerHTML = "<img src=" + monthpic + ">";
						}).fail(function() {
							if (debug) console.log(window.location + '/' + monthname + '.jpg' + ' not found');
						})
						lastmonthname = monthname;
						$(".fc-button").click(function() { // fc-button ist für vor UND zurück !
							activemonth = $('div.fc-center h2').text();	// fc 4.3
							if (debug) console.log('button clicked, month:', activemonth, 'lastmonth:', lastmonthname);
							monthname = activemonth.split(" ")[0];
							if (lastmonthname != monthname) {
								lastmonthname = monthname;
								$.ajax({
									type: "HEAD",
									async: true,
									url: window.location + '/' + monthname + '.jpg'
								}).done(function() {
									if (debug) console.log("new monthpic found");
									monthpic = path + '/' + monthname + '.jpg';
									if (document.getElementById('actMonth') != null) document.getElementById('actMonth').innerHTML = "<img src=" + monthpic + ">";
								}).fail(function() {
									if (debug) console.log(window.location + '/' + monthname + '.jpg' + ' not found');
								})
							}
						});
					}
				}
			}
		}, 2500);	// was: 1000
	}
})
