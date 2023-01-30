<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


ini_set('display_errors', 1);


//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

//	use DigitalMx\jotr\Utilities as U;
//
// 	use DigitalMx\jotr\Refresh;
//



	$Plates = $container['Plates'];

$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Introduction',
	'extra' => '<style>body {font-family: "Times New Roman", Times, serif;width:90vw;max-width:800px;}</style>',
);

echo $Plates->render('head',$meta);
echo "<body>";
echo $Plates->render('title',$meta);
//END START

?>



<h3>Today In The Park</h3>
<p>
“Today in the Park” is a internet-based system to provide visitors with key information about this day in Joshua Tree National Park.  It was inspired by the purchase of TV displays for the visitor centers, which provided a way to communicate park information directly to visitors in real time.
</p>

<h3>Overview</h3>
It includes
<ul>
	<li>Weather for several days for several locations, including localized Jumbo Rocks</li>
	<li>Sun and Moon rising and setting times and moon phase.
	<li>Live data from Lost Horse RS for air quality, temperature, and wind</li>
	<li>Visitor Advice and Park Announcements</li>
	<li>Weather.gov alerts, edited for local use.</li>
	<li>Campsite information, including live availability from recreation.gov </li>
	<li>Entrance Fees and where to buy</li>
	<li>Events for today and next day</li>
</ul>

The data is provided in four formats:
<ol>
	<li>A “slide show” of pages designed for TV screen.  Each viewing location can choose which sections (slides) appear in the rotation. The TV requires only internet access (e.g., any modern Samsung TV) and it only accesses the internet once every 15 minutes for updates.  (<a href='/rotate.php' target='rotate'>Rotate</a>)</li>
	<li>A one-page printed form, similar to what is used at JTVC.  Includes weather, announcements, and calendar. (<a href='summary.php' target='summary'>Today Summary</a>)</li>
	<li>A publicly accessible web site that visitors could access themselves.(<a href='today.php' target='today'>Today in Joshua Tree National Park</a>)</li>
	<li>A multi-page document can be printed from the "today" site, and one or more pages posted.  (e.g, fees page) </li>

</ol>

The advantages of this system are:
<ul>
	<li>	Most of the information is self-updating, using a variety of internet sources such as weather.gov and the eps site.</li>
	<li>	Manual information is entered in an easy-to-use web form that can be updated on any computer.</li>
	<li>	It has a built-in calendar that’s easy to update with 1-off or repeating events.  Pop-up talks can be entered in the morning for use that day or removed if cancelled.</li>
	<li> It provides a centralized source for key information, so the same information is available everywhere.
	<li>	It provides near-real-time information to every visitor center or web-site user.</li>
	<li>	It eliminates need for rangers to research information and compose signs for visitor centers.</li>
	<li>	It eliminates the need for any special hardware for TV interface.</li>
</ul>
<h3>Resources</h3>
<div class=' inleft2 width50 auto'>

<ul>
<li><a href='/about.php'>More info about the Site</a>
<li><a href='/pages.php' target='pages'>List of Pages</a>
<li><a href='/today.php' target='today'>Today in the Park web site</a>
<li><a href='/summary.php' target='today'>Today: one-page summary</a>
<li><a href='/rotate.php' target='rotate'>Rotating Pages for TV Screen</a>
</ul>
</div>
