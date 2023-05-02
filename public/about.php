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
	'title' => 'About Today in the Park',

	]);


echo $Plates->render('head',$meta);
echo $Plates->render('body',$meta);
echo $Plates->render('title',$meta);
//END START

?>
<h3>How it works</h3>
<p>The site works by periodically (like every 1/2 hour) querying various web resources to get updated information on weather, alerts, air quality, etc.  This information is stored until the next update.  </p>
	When a web page is requested, the stored information is
	retrieved and compiled into the form needed for display,
	and then sent a template for the requested web page.
	The template does the final formatting for displaying the page.
</p>
<p>The site runs on a linux-based server and is built using a language called "PHP", which is commonly used for web sites, including the nps.gov sites for parks.</p>

<div width='100%' class='center'><img src='/images/structure.jpg' alt='Structure Drawing'  class='auto'/></div>
<h3>Information Sources</h3>
<p>Weather information comes from both weather.gov (NOAA) and weatherapi.com (commercial site).  The NOAA data is focused better on a geographic area, so it forecasts for Jumbo Rocks, not just 29 palms.  The weatherapi data is substituted if weather.gov fails, but the data is for 29 Palms and Indio, not Jumbo rocks and Cottonwood.</p>
<p>Current data comes from the sensors at Lost Horse Ranger Station, updated every few hours.  If data is not available, it is reported as "n/a". </p>
<p>Astro data (sun, moon) is from weatherapi.com. Should be non-controversial.</p>
<p>Air quality is from airnow.com (EPS site).</p>
<p>Fire Danger is entered manually by rangers.</p>
<p>Alerts are entered manually, by rangers, but informed by weather.gov alerts for Joshua Tree National Park.</p>
<p>Notices and Advice are entered manually by rangers</p>
<p>The Calendar is drawn from the jotr calendar on nps.gov.  That is supplemented by a built-in local calendar, where it is easy to quickly add events not on the nps calendar.  Any of the events can be marked as cancelled; they continue to show on-line but with a "Cancelled" legend.  </p>
<p>Campground status (reserved, closed) is manually entered by rangers. Availability for reserved sites comes from recreation.gov, updated every 30 minutes. Available sites in First-Come First-Served campgrounds is manually entered.  Tabs next to the availability number indicates the age of the data; data over 12 hours old is removed.</p>
<p>The web pages all use Google Rubik font. </p>

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

<h3>Web Sites</h3>
<p>There are (at least) 3 different web sites used for the system:
<dl>
	<dt>Live</dt>
		<dd>This is the live site used for the TV displays or anything intended for public access.</dd>
	<dt>Jotrx</dt>
	<dd>This is a "test" site.  It is exactly like the live site, but the data is different.  This is for admin users to make changes on the admin page and see what the results are, without affecting the live site.
	<dt>Beta</dt><dd>This is a site used to test software before it is moved to the live site.
	<dt>others</dt><dd>There may be other versions of the site used for development

</dl>

<h3>Help</h3>
<p>If you want to change any of the information, or if you have any problems or comments, contact John Springer. Phone or text to (503)329-7909 or email john@digitalmx.com.</p>
