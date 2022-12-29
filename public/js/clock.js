function display_c(){
var refresh=1000; // Refresh rate in milli seconds
mytime=setTimeout('display_clock()',refresh)
}

function display_clock() {
var x = new Date()
var ampm = x.getHours() >=12 ? ' PM' : ' AM';
hours = x.getHours() % 12;
hours = hours ? hours : 12;
// var x1=(x.getMonth() + 1) + "/" + x.getDate() + "/" + x.getFullYear();
var ctime = hours+':'+x.getMinutes()+ampm;
document.getElementById('clock').innerHTML = ctime;
display_c();
 }

/* on page, div id='clock'
	 body onload=display_clock();
*/
