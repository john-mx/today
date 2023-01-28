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

$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'About the Today Site',
	'extra' => "",

);

echo $Plates->render('head',$meta);
echo "<body>";
echo $Plates->render('title',$meta);
//END START

?>
<h3>About Today</h3>
<p>The “Today” site  is a web-based presentation of today’s conditions and activities in the park. It includes weather, alerts and advice, events, and (soon) campsite availability.   It was designed to make timely information available to visitors with minimal effort from park rangers and staff. The information is produced in four formats:</p>
<ol>
<li>You can display it on a internet-enabled TV screen as a rotating series of slides (only tested on Samsung). Uses large type so will look funny on computer.<br />
<a href='/rotate.php' target='rotate'><code><?=SITE_URL?>/rotate.php</code></a>
<li>You can print a 1-page summary, including weather, alerts, and events, for use in visitor centers or campgrounds.<br />
<a href='summary.php' target='summary'><code><?=SITE_URL?>/summary.php</code></a>
<li>Anyone can view all the key park info. This is a publicly available site.<br>
<a href='/today.php' target='today'><code><?=SITE_URL?>/today.php</code></a>
<li>The web site above can be printed as a 5-page document. (You can also render and print each section individually.  See the page list below.)
</ol>
<p>For a complete list of available pages and functions, go to <a href='/pages.php' target='pages'>/pages.php</a></p>
<p>
<h3>How to set up the rotating display on a Samsung TV:</h3>
</p>
<ol>
<li>Turn on the TV and press Source on remote or press Home.  Press  down and right arrow on the remote (may be circle around central button) to move across sources and get to the “Internet:” choice, and select it.
<li> If you get a page with bookmarks that shows a "Rotate" site, choose it from the bookmarks, and you're basically done.
<li> Otherwise
	<ol type='a'>
<li>Move to the URL field and press Enter.  You'll get a keyboard on the screen.
<li>Type this url into the url field using the arrow keys and center “Enter” button to navigate the keyboard. <br /> <code>https://jotr.digitalmx.com/rotate.php</code><br />
<li> Scroll to and press the “Done” key when finished.  The Today page should come up.
<li> Move the pointer to the Star at the right of the address bar and press Enter to bookmark the site.<br />(Choose local, I think).

<li>(One time setup) Use the arrow keys to navigate to the 3-bar menu pattern at very top right ,and choose Settings.  Under General, set the home page to Bookmarks, and Enable the “hide menu bar automatically” choice.
</ol>
<li> Just to be sure... At the top right (use arrow keys to scroll up until menu bar appears), make sure you are set for “100%”. Choose plus and minus tabs to change.
</ol>
<p>The screen will change every 15 seconds.  (The first iteration may take longer.) It's just one page, broken into sections that sequentially are visible on the screen.  The page updates every 15 minutes to pick up any new information.<br />
If something stops working, try moving the pointer to the top and press the "Refresh" button next to the address bar.
</p>
<h3>Localization</h3>
There are a few settings that can be made locally on the display device you're using (TV, for example.)  You access the local settings by clicking on the "now" time at the left side of the title bar on the rotating TV display.
<ol>
<li>You can choose the name of the site, which will display in the title bar like "Welcome to the Cottonwood Visitor Center."
<li>You can choose which of the available pages appears in the rotation sequence on your device.
<li> You can choose the rotation rate: how many seconds per slide.
</ol>
These settings are stored locally on the display device for 48 hours after last usage, and then revert to default system settings.
<h3>Printing Today Pages</h3>
<p>
The page "today.php" can be printed from a web browser, and appropriate pages posted in a Visitor Center.
</p>
 <p>
 Printing will <ul>
 <li>change the margins to 0.5" on 8.5x11 pages
 <li> reduce the type size,
 <li> remove the "Current temperature" entry.
 </ul>
It should fit on five pages, each with room for expanding content. (today and alerts on page 1;  weather on page 2,  calendar on page 3; campgrounds on page 4; fees on page 5). Campgrounds page doesn't include site availability, so is fairly static.
</p>
<p>If printed to a PDF, then the pdf can easily be distributed electronically</p>

<h3>Information Sources</h3>
<p>Weather information comes from both weather.gov (NOAA) and weatherapi.com (commercial site).  The NOAA data is focused better on a geographic area, so it forecasts for Jumbo Rocks, not just 29 palms.  The weatherapi data is substituted if weather.gov fails, but the data is for 29 Palms and Indio, not Jumbo rocks and Cottonwood.</p>
<p>Current data comes from the sensors at Lost Horse Ranger Station, updated every few hours.  If data is not available, it is reported as "n/a". </p>
<p>Astro data (sun, moon) is from weatherapi.com. Should be non-controversial.</p>
<p>Air quality is from airnow.com (EPS site).</p>
<p>Fire Danger is entered manually by rangers.</p>
<p>Alerts are entered manually, by rangers, but informed by weather.gov alerts for Joshua Tree National Park.</p>
<p>Notices and Advice are entered manually by rangers</p>
<p>The Calendar is manually maintained by local rangers. This makes it possible to quickly add or remove events.</p>
<p>Campground status (reserved, closed) is manually entered by local rangers. Available sites in First Come First Served campgrounds is manually entered.  The available campsite data for reserved sites is (will) come from recreation.gov, updated every hour or so.  Availability data more than a few hours old will be displayed as '?'. Only the Rotate page shows availability.</p>
<p>The web pages all use NPS Frutiger font. You're welcome. </p>

<h3>Help</h3>
<p>If you want to change any of the information, or if you have any problems or comments, contact John Springer. Phone or text to (503)329-7909 or email john@digitalmx.com.</p>
