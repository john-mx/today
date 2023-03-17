<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';


	$Plates = $container['Plates'];

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Today in the Park',
	]);

echo $Plates->render('head',$meta);

echo $Plates->render('title',$meta);
//END START

//$here = $_SERVER['
$here = 'https://jotr.digitalmx.com/';
$date = date('M d, Y');
?>



<h3>Today In The Park</h3>
<p>
“Today in the Park” is a internet-based system to provide visitors with key information about this day in Joshua Tree National Park.  It was inspired by the purchase of TV displays for the visitor centers, which provided a way to communicate park information directly to visitors in real time:
<ul>
	<li>Weather for several days for several locations, including theJumbo Rocks campground.</li>
	<li>Sun and Moon rising and setting times and moon phase.
	<li>Live data from Lost Horse RS for air quality, temperature, and wind</li>
	<li>Visitor Advice, Park Announcements, and
	Weather.gov alerts.</li>
	<li>Campsite information, including live availability from recreation.gov </li>
	<li>Events for the next 3 days.</li>
</ul>
</p>

<h3>Viewing The Data</h3>
The data is provided in four formats:
<ol>
	<li><a href='/rotate.php' target='rotate'>A “slide show” of pages.</a><br />
	Designed for TV screens. Each viewing location can choose which sections (slides) appear in the rotation. The TV requires only internet access (e.g., any modern Samsung TV) and it only accesses the internet once every 15 minutes for updates.  </li>
	<li><a href='summary.php' target='summary'>A one-page summary.</a><br />
	Designed to be posted at Visitor Centers or elsewhere.  Includes weather, announcements, and calendar. </li>
	<li><a href='today.php' target='today'>A complete web site</a><br />
	Has more info than the other pages.  Can be viewed on-line or printed as a multi-page document, with one page per topic.  (e.g, fees page, or weather) </li>

</ol>
<h3>Advantages</h3>
<ul>
	<li>	Most of the information is self-updating, using a variety of internet sources such as weather.gov and the eps site.</li>
	<li>	Manual information is entered in an easy-to-use web form that can be updated on any computer.</li>
	<li>	It retrieves the calendar from nps.gov, plus it has a built-in calendar that’s easy to update and can supplement or replace items on the nps calendar. Events can be marked as cancelled if necessary.</li>
	<li> It provides a centralized source for key information, so the same information is available everywhere.
	<li>	It provides near-real-time information to every visitor center or web-site user.</li>
	<li>	It eliminates need for rangers to research information and compose signs for visitor centers.</li>
	<li>	It eliminates the need for any special hardware for TV interface.</li>
</ul>
<h3>Resources</h3>
<div class='inleft2'>
<ul>
<li><a href='/about.php' target='about'>About this site.</a> How it works.
<li><a href='/setup.php' target='setup'>TV setup.</a> How to view the pages on a TV screen.
</ul>
<b>Admin Access</b>
Pages below require password to view

<ul>
<li><a href='/pages.php' target='pages'>Index of all site Pages</a>
<li><a href='/ranger.php' target='ranger'>Ranger Login</a>

</ul>
</div>
For more information contact the developer.
John Springer  <a href='mailto:john@digitalmx.com'>email</a> ph:503-329-7909
<?php echo $Plates->render('sig'); ?>
