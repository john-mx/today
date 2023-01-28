
function load_snap() {
	startRotation(rdelay,pageList);
	display_clock();


};

function startRotation(secs,idList){
	var intID = setInterval(rotateDivs,secs*1000,idList);
}

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
	// assume tv window is 1920 x 1080 (16x9)
	var elid = element.id;
	var contentHeight = element.offsetHeight;
	var contentWidth = element.offsetWidth;
 // content
	var titleHeight = document.getElementById('titles').offsetHeight;

	var pageHeight = contentHeight + titleHeight;
	var  winHeight = window.innerHeight;
	var  winwidth = window.outerWidth;

	var  calcHeight = contentWidth * 9/16;

	var availHeight = winHeight - titleHeight-60;

	var scaleRatio = availHeight/contentHeight;
	scale = Math.min(1,scaleRatio); // never grow
	scale = Math.max(0.65,scale) // never less than 0.75
	var scaler = Math.round(scale*100);
// alert ('content ' +contentWidth +'x' + contentHeight +' avail ' + winwidth +' x ' +availHeight + ' scale '+ scaleRatio + ' (' + scaler + '%)');
	if (typeof(element) !== 'object'){
		alert("[load_snap] element not object: " + elid);
	}
	element.style.transform = "scale("+scale+")";
	var scaler = Math.round(scale*100);
	var scalerId = elid+"-scale";
	var scaleMessage = document.getElementById(scalerId);

	if (scaleMessage && (scaler < 100)){
		scaleMessage.innerHTML = scaler + '%';
	}


}


function rotateDivs(idList){
/* function grabs all the division with id starting with "page".
	They should (or all but one) initially be set as display:none.
	The script will run through turning on one div after the other
	and then repeat.

	vars are static (function.name) so they are preserverd across runs.

	In the last step, the page is resized (scaled) to make sure the
	bottom is still in the window.

*/

/* Initialize */

	if ( typeof rotateDivs.dlist == 'undefined' ) {
//  		rotateDivs.dlist = document.querySelectorAll('div[id^="page"]');

	if (!idList) {
		 	rotateDivs.dlist = document.querySelectorAll('div[id^="page"]');
	} else {
		rotateDivs.dlist = document.querySelectorAll(idList);
	}

		rotateDivs.dsize = rotateDivs.dlist.length - 1;

// 		alert("dsize " + rotateDivs.dsize);
		for (let i = 0; i < rotateDivs.dsize; i++) {
			rotateDivs.dlist[i].style.display='none';
		}
		 rotateDivs.pointer = 0;
      rotateDivs.last = rotateDivs.dsize;
//       alert ("Initialized. " + rotateDivs.dsize + " divs" );
	}


    offdiv = rotateDivs.dlist[rotateDivs.last];
    	offdiv.style.display='none';

	ondiv = rotateDivs.dlist[rotateDivs.pointer];
		ondiv.style.display = 'block';

	++rotateDivs.pointer;
	rotateDivs.last = rotateDivs.pointer -1;

	if (rotateDivs.pointer > rotateDivs.dsize){
		rotateDivs.pointer = 0;
		rotateDivs.last = rotateDivs.dsize;
	}
	if (rotateDivs.pointer == 1){
		rotateDivs.last = 0;
	}
// alert ("pointer " + rotateDivs.pointer + " last " +rotateDivs.last );
document.getElementById('loadholder').style.display='none';
doResize(ondiv);
return true;
}

