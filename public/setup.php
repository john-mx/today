<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

//	use DigitalMx\jotr\Utilities as U;
//
// 	use DigitalMx\jotr\Refresh;
//



	$Plates = $container['Plates'];
//

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Setting Up Rotating Display',

	]);


echo $Plates->render('head',$meta);

echo $Plates->render('title',$meta);
//END START

?>
<h3>How to set up the rotating display on a Samsung TV:</h3>
One thing the system generates is a web page that produces
rotating "slides" on a tv screen.  Here's how to use it.
<ol>
<li>Turn on the TV and press Source on remote or press Home.  Press  down and right arrow on the remote (may be circle around central button) to move across sources and get to the “Internet:” choice, and select it.
<li> If you get a page with bookmarks that shows a "Rotate" site, choose it from the bookmarks, and you're basically done.
<li> Otherwise
	<ol type='a'>
<li>Move to the URL field and press Enter.  You'll get a keyboard on the screen.
<li>Type this url into the url field using the arrow keys and middle “Enter” button to navigate the keyboard. <br /> <code>https://jotr.digitalmx.com/rotate.php</code><br />
<li> Scroll to and press the “Done” key when finished.  The rotating Today page should come up.
<li> Move the pointer to the Star at the right of the address bar and press Enter to bookmark the site.<br />(Choose local, I think).

<li>(One time setup) Use the arrow keys to navigate to the 3-bar menu pattern at very top right ,and choose Settings.  Under General, set the home page to Bookmarks, and Enable the “hide menu bar automatically” choice.
</ol>
<li> Just to be sure... At the top right (use arrow keys to scroll up until menu bar appears), make sure you are set for “100%”. Choose plus and minus tabs to change.
</ol>
<p>The screen will change every 15 seconds.  (The first iteration may take longer.) It's just one page, broken into sections that sequentially are visible on the screen.  The page updates every 15 minutes to pick up any new information.<br />
If something stops working, try moving the pointer to the top and press the "Refresh" button next to the address bar.
</p>
<h3>Localization</h3>
There are a few settings that can be made locally on the display device you're using (TV, for example.)  You access the local settings by clicking on the "Now" time at the left side of the title bar on the rotating or scrolling TV display.
<ol>
<li>You can choose the name of the site, which will display in the title bar like "Welcome to the Cottonwood Visitor Center."
<li>You can choose which of the available pages appears in the rotation sequence on your device.
<li> You can choose the rotation rate: how many seconds per slide.
</ol>
These settings are stored locally on the display device for 48 hours after last usage, and then revert to default system settings.
<h3>Printing Today Pages</h3>

<h3>Scaling</h3>
<p>TV pages are much wider than they are long (16:9 ratio).  The length of the content on the page is not entirely predictable, as there are lists of notices on some pages, and weather information can take more words.</p>
<p>To make sure all information is visible on the screen, each page is scaled so the content length fits within the available screen space.  This will show up at the very bottom of the page as the % scaling applied to the page.  If pages are too compressed, it may be necessary to remove some items for the page.</p>
<h3>Help</h3>
<p>If you want to change any of the information, or if you have any problems or comments, contact John Springer. Phone or text to (503)329-7909 or email john@digitalmx.com.</p>
