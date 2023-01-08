
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

	This function now called as part of the rotate script,
	as each page comes into view.
*/

function doResize(elementID) {
	if (typeof(elementID)!=='object') {
	var element = document.getElementById(elementID);
	} else { // its already an object
		element = elementID;
	}

	var elid = element.id;
	var contentheight = element.innerHeight;


; // content
	var titleheight = document.getElementById('titles').offsetHeight;

	var pageheight = contentheight + titleheight;
	var  winheight = window.innerHeight;
	var  winwidth = window.outerWidth;

	var availheight = winheight-titleheight-50;

	var scaleRatio = availheight/contentheight;
	scale = Math.min(1,scaleRatio); // never grow
	scale = Math.max(0.75,scale) // never less than 0.75

// alert ('- win ' + winheight +' av =' + availheight + ' cont ' + contentheight + ' scale '+ scale);
	if (typeof(element) !== 'object'){
		alert("[load_snap:38] element not object: " + elid);
	}
	element.style.transform = "scale("+scale+")";
	var scaler = Math.round(scale*100);
	var scalerId = elid+"-scale";
	var scaleMessage = document.getElementById(scalerId);
	if (scaleMessage && (scaler < 100)){
		scaleMessage.innerHTML = scaler + '%';
	}


}


