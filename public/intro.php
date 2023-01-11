<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


#ini_set('display_errors', 1);

//BEGIN START
	require  'init.php';

	$Plates = $container['Plates'];
	$Today = $container['Today'];
//END START

$meta=array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Introduction',
	'extra' => "",

	);
echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);


?>
<p>
“Today in the Park” is a internet-based system to provide visitors with key information about this day in Joshua Tree National Park.
</p>

It includes
<ul>
	<li>Weather today and next 2 days, including sun and moon.</li>

	<li>Live data from Lost Horse RS for air quality, temperature, and wind</li>
	<li>Visitor Advice and Park Announcements</li>
	<li>Weather.gov alerts, edited for local use.</li>
	<li>Campsite information, including live availability from rec.gov (in process)</li>
	<li>Fees and where to buy</li>
	<li>Events for today and next day</li>
</ul>

The data is provided in four formats:
<ol>
	<li>A “slide show” of pages designed for TV screen.  The “admin” can choose which pages appear in the rotation. The TV requires only internet access (e.g., any modern Samsung TV) and it only accesses the page once ever 15 minutes for updates.  (<a href='/rotate.php'>Rotate</a>)</li>
	<li>A one-page printed form, similar to what is used at JTVC.  Includes weather, announcements, and calendar. (<a href='summary.php'>Today Summary</a>)</li>
	<li>A publicly accessible web site that visitors could access themselves.(<a href='today.php'>Everything</a></li>
	<li>A multi-page document can be printed from the "Everything" site, and one or more pages posted.  (e.g, fees page) </li>

</ol>

The advantages of this system are:
<ul>
	<li>	Most of the information is self-updating, using a variety of internet sources such as weather.gov and the eps site.</li>
	<li>	Manual information is in an easy-to-use web form that can be updated on any computer.</li>
	<li>	It has a built-in calendar that’s easy to updated with repeating events and extra information.  Pop-up talks can be entered in the morning for use that day or removed if cancelled.</li>
	<li> It provides a centralized source for key information, so the same information is available everywhere.
	<li>	It provides near-real-time information that can be available in every visitor center.</li>
	<li>	It eliminates need for rangers to research information and compile signs for visitor centers.</li>
	<li>	It eliminates the need for any special hardware.</li>
</ul>

