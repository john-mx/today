
function load_snap() {
	startRotation();
	display_clock();


};

function startRotation(){
	rotateDivs();
	var intID = setInterval(rotateDivs,snapVars['rdelay']*1000);
}


function rotateDivs(){
	var idList = snapVars['pageList'];
	//alert ('idlist: ' + idList.length);
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

		if (typeof idList == 'undefined') {
				rotateDivs.dlist = document.querySelectorAll('div[id^="page"]');
		} else {
			rotateDivs.dlist = document.querySelectorAll(idList);
		}
	//alert ('page1: ' + idList[1]);
//  alert("dsize (40): " + rotateDivs.dlist.length);
		
rotateDivs.dsize = rotateDivs.dlist.length - 1;
// 	 alert("dsize (42): " + rotateDivs.dsize.length);

		for (let i = 0; i < rotateDivs.dsize; i++) {
			rotateDivs.dlist[i].style.display='none';
		}
		 rotateDivs.pointer = 0;
      rotateDivs.last = rotateDivs.dsize;
//    s     alert ("Initialized. " + rotateDivs.dsize + " divs" );
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
// document.getElementById('loadholder').style.display='none';

// resize page to amke sure it fits onto screen
doResize(ondiv);
elid = ondiv.id;

	if (elid == 'page-notices'){
		//alert ("on " + elid);
		oneAdvice = document.getElementById('randomAdvice');
		oneAdvice.innerHTML = randomItem(snapVars['adviceList']);
	}

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
	var titleHeight = document.getElementById('head').offsetHeight;
	//titleHeight = 0; //each page has its own title now
	var pageHeight = contentHeight + titleHeight;
	var  winHeight = window.innerHeight;
	var  winwidth = window.outerWidth;

	var availHeight = winHeight - titleHeight-60;
	//var availHeight = 1080 - titleHeight;

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
	//var scalerId = elid+"-scale";
	var scalerId = "scaler";
	var scaleMessage = document.getElementById(scalerId);

	//if (scaleMessage && (scaler < 100)){
		scaleMessage.innerHTML = scaler;
	//}


}

/* choose a random item from a list to display
	this is called by the rotate scvript so you get a
	different item on each rotation.
*/

function randomItem (itemList) {
	var item = itemList[Math.floor(Math.random()*itemList.length)];
	return item;
}
