<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require  'init.php';

	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	#use DigitalMx\jotr\Today;


	$Plates = $container['Plates'];
	$Defs = $container['Defs'];

	$Today = $container['Today'];


//END START


echo $Plates->render('start',['meta'=>['title'=>'Today Index']]);
;

//Log::error('starting index' . __FILE__ . __LINE__);


if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	if (array_key_exists('rebuild',$_POST)) {
			$Today->rebuild(false);
	}
	if (array_key_exists('reload',$_POST)) {
			$Today->rebuild(true);
	}
}
?>

<p>This project generates the "Today in the Park" report in a variety of formats.</p>

<ol>
<li> Local data, such as park alerts, closures, and fire level, are entered manually by an admin using the admin.php page.
<li> External data is collected every few hours from a number of places that report information like weather, air quality, astronomical data, and more. This date is stored on the site.
<li> The "today" page is built by combining all this data in a user-friendly format.
<li> The today page can be retrieved with a style code by appending ?style_name
</ol>
<h4>Pages</h4>
<table class='left'>

<tr><td class='left'>URL</td><td class='left'>Result</td></tr>
<tr><td class='left'><a href="/today.php" target='today'>/today.php</a>
</td><td class='left'>Regular web page with all data</td></tr>
<tr><td class='left'><a href="/today.php?scroll" target='scroll'>/today.php?scroll</a>
</td><td class='left'>Page slowly scrolls to the bottom, then restarts</td></tr>
<tr><td class='left'><a href="/today.php?snap" target='snap'>/today.php?snap</a>
</td><td class='left'>Page displays a section for 10 seconds, then next section, and so on, before starting over.</td></tr>
<tr><td class='left'><a href="/today.php?print" target='print'>/today.php?print</a>
</td><td class='left'>Regular web page prepared for printing with smaller type and defined pages.</td></tr>
<tr><td class='left'><a href='/admin.php' target='admin'>Admin page</a> </td><td class='left'>(password 'abcd')</td></tr>
<tr><td class='left'><a href='/set_properties.php' target='_blank'>Reset properties.json</a> </td><td class='left'>(only used for reference, not live).</td></tr>
<tr><td class='left'><a href='/cron_update.php' target='_blank'>Run cronfile</a></td><td class='left'> (Updates all the external caches; should run automatically).</td></tr>
</table>





<!--
<table>
<tr><td class='left'>
	<div class='likebutton'><a href='/pages/wapi.html' target='today'>wapi</a></div></td>
	<td class='left'>Static Page using weatherapi.com for weather</td></tr>

<tr><td class='left'>
	<div class='likebutton'><a href='/pages/wgov.html' target='today'>wgov</a></div></td>
	<td class='left'>Static Page using weather.gov for weather</td></tr>

<tr><td class='left'>
	<div class='likebutton'><a href='/pages/scroll.html' target='scroll'>scroll</a></div></td>
	<td class='left'>Like wapi, but with smooth scrolling from top to bottom.  (Works on smart tv or computer, does not work on phone.)</td></tr>

<tr><td class='left'>
	<div class='likebutton'><a href='/pages/snap.html' target='snap'>snap</a></div></td>
	<td class='left'>Like wapi, but divided into sections, and snaps every 10 seconds to athe next section.  Intended for TV monitor.  Allow 10 seconds for animation to start.</td></tr>


<tr><td class='left'>
	<div class='likebutton'><a href='/pages/ptext.html' target='text'>Text only</a></div></td>
	<td class='left'><tt>just plain text</tt></td></tr>

<tr><td class='left'>
	<div class='likebutton'><a href='/pages/email.html' target='email'>Limited styles for email</a></div></td>
	<td class='left'>For email, all the style information has to be defined "inline", without using style sheets.  This version does that, and sh9uld be good for copy/paste into an email. View page, select all, copy, and paste into an email.</td></tr>
<tr><td class='left'>
	<div class='likebutton'><a href='/pages/print.html' target='print'>print version</a></div></td>
	<td class='left'>Designed for printing on 8.5x11; smaller type, condensed layout. takes 2 pages.<br>
		Will be available as a pdf page, but it isn't working right yet.
	<!~~ <a href="/pages/" . date('Y-d-m') .".pdf">PDF version is here.</a> ~~>
	</td></tr>


</table>


<form class='in2' method='POST'>
Use this button to rebuild all the pages.  Caches will be refreshed only if they are due.
<button type='submit' name='rebuild' value=true>Rebuild pages</button>
	<br><br>
	Use this button to refresh all the external data now, and then rebuild the pages.
	<button type='submit' name='reload' value=true>Reload and Rebuild</button>
</form>

 -->
