
function load_snap() {
	startRotation(rdelay,pageList);
	display_clock();
	doResize('page-today');
};
/* resize the content portion of the page
	to make sure it is all on the screen.
	So available is screen height - title height
	current size is element height
	resize to fit.
*/

function doResize(elementID) {
	var el = document.getElementById(elementID);
	var contentheight = el.offsetHeight; // content
	var titleheight = document.getElementById('titles').offsetHeight;

	var height = contentheight + titleheight;
	var winheight = window.outerHeight;
	var winwidth = window.outerWidth;

	var availheight = winheight-titleheight-50;


	scale = Math.min(1,availheight/contentheight);
 alert (elementID +'- win ' + winheight +' av =' + availheight + ' cont ' + contentheight + ' scale '+ scale);

	el.style.transform = "scale("+scale+")";


}
