/*	new approach for update month picture upon next - prev - home click
	evaluate Text in Month div fc-left: this is the Month name in current locale
	build pic name from that.
	pic files must be named like months in current locale !
*/

$(document).ready(function() {
    setTimeout(function() { // ohne Timeout wird activemonth nicht belegt !
        var debug = false;

        //	var activemonth = $('div.fc-left h2').text();	// fc 3.x
		var activemonth = $('div.fc-center h2').text();	// fc 4.3
        if (debug) {
            console.log('activemonth:', activemonth);
        }
        var monthname = activemonth.split(" ")[0];

        if (monthname.length > 0)  {
            var path = document.location.pathname;
            var monthpic = path + '/' + monthname + '.jpg';
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
            $(".fc-button").click(function() { // fc-button ist für vor UND zurück !
                //	activemonth = $('div.fc-left h2').text();	// fc 3.x
				activemonth = $('div.fc-center h2').text();	// fc 4.3
                if (debug) console.log('button clicked, month:', activemonth);
                monthname = activemonth.split(" ")[0];
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
            });
        }
    }, 10);
})
