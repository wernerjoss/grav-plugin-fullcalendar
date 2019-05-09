// jquery required !
$(document).ready(function(){
	var debug = false;
	var date = new Date();
	var month = date.getMonth();
	if (debug)  console.log('Month(nr.):', month);
	var picnames = ['January.jpg', 'February.jpg', 'March.jpg', 'April.jpg', 'May.jpg', 'June.jpg', 'July.jpg', 'August.jpg', 'September.jpg', 'October.jpg', 'November.jpg', 'December.jpg'];
	var path = document.location.pathname;
	var mpic = path + '/' + picnames[month];
	if (debug)  console.log('mpic:', mpic);
	$.ajax({
		type: "HEAD",
		async: true,
		url: window.location  + '/' + picnames[month]
		}).done(function(){
			if (debug)  console.log("mpic found");
			if (document.getElementById('actMonth') != null)	document.getElementById('actMonth').innerHTML = "<img src=" + mpic + ">";
		}).fail(function () {
			if (debug)  console.log(window.location + '/' + picnames[month] + " not found");
	})
})


