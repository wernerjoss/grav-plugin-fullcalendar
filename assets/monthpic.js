/*	new approach for update month picture upon next - prev - home click
	evaluate Text in Month div fc-center: this is the Month name in current locale
	build pic name from that.
	pic files must be named like months in current locale !
*/

$(document).ready(function() {
    var debug = false;
    var lastmonthname = '';
    setInterval(function() {    // statt setTimeout() - siehe https://www.w3schools.com/jsref/met_win_settimeout.asp
    	var activemonth = $('div.fc-center h2').text();	// fc 4.3
        if (debug) {
            console.log('activemonth:', activemonth);
        }
        var monthname = activemonth.split(" ")[0];
        if (monthname.length > 0)  {
            var path = document.location.pathname;
            var monthpic = path + '/' + monthname + '.jpg';
            if (monthname != lastmonthname) {
                if (debug) console.log('monthpic:', monthpic);
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
                    //	activemonth = $('div.fc-left h2').text();	// fc 3.x
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
    }, 250);
})
