
function doResize(elementID) {
	var el = document.getElementById(elementID);
	var pageheight = el.offsetHeight; // content
	var titleheight = document.getElementById('titles').offsetHeight;

	var height = pageheight + titleheight;
	var winheight = window.innerHeight;

	var availheight = winheight-titleheight-50;


	scale = availheight/pageheight;
//alert ('page avail =' + availheight +','+ winheight + ' '+scale);
	el.style.transform = "scale("+scale+")";


}
