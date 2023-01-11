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
	'subtitle' => 'Today Project Index',
	'extra' => "",

	);
echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);



//Log::error('starting index' . __FILE__ . __LINE__);
//Log::info('Index page');

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

<h4>Pages</h4>
<table class='index'>
<tr><td >Link</td><td >Result</td></tr>
<tr class='bg-yellow'><td colspan='2'>Information Pages</td></tr>

<tr><td><a href='about.php'>About</a></td><td>General information about the site.</td></tr>
<tr><td><a href='/'>Overview</td><td>Overview of the project</td></tr>

<tr class='bg-yellow'><td>'Today' Pages</td><td></td></tr>

<tr><td ><a href="/today.php" target='today'>Everything</a>
</td><td >Public web site with all data. Can be printed (5 pages).  Does not include campsite availability. (or should it?)</td></tr>

<tr><td ><a href="/summary.php" target='summary'>Today</a>
</td><td >One page summary of today's conditions, alerts, and events. </td></tr>


<tr class='bg-yellow'><td >Display Pages</td><td>Animated pages designed for use on TV screen.</td></tr>
<tr><td ><a href="/today.php?scroll" target='scroll'>Scroll</a>
</td><td >(not recommended) Everything page slowly scrolls to the bottom, then restarts</td></tr>
<tr><td><a href="/rotate.php" target='snap'>Rotate</a><td> (reommended) Large type version designed for TV. Rotates through topics, changing every 15 seconds.  (You can choose which pages (or just one page) appear in the rotation using the admin page. ) Uses "snap" animation unless url is "/rotate.php?nosnap" </td></tr>

<tr class='bg-yellow'><td>Topic Pages</td><td>Individual topics from the 'Everything' page</td></tr>
<tr><td><a href='/pager.php?weather'>Weather</td><td>3-day forecast, Jumbo Rocks, Black Rock and Cottonwood</tr>
<tr><td><a href='/pager.php?fees'>Fee Schedule</td><td>All fees and where to buy</td></tr>
<tr><td><a href='/pager.php?events'>Calendar</td><td>Events for next 3 days</td></tr>
<tr><td><a href='/pager.php?notices'>Notices</td><td>Alerts and Notices</tr>
<tr><td><a href='/pager.php?campgrounds'>Campgrounds</td><td>Campground status, sites, and fees</tr>
<tr>
<tr><td colspan='2' class='bg-yellow'>Ranger Admin Pages</td></tr>

<tr><td ><a href='/admin.php' target='admin'>Admin page</a> </td><td >Ranger sets things like alerts, closures, campground status, calendar events, etc.</td></tr>
<tr><td ><a href='/local.php' target='local'>Local Settings Page</a> </td><td >Settings for local display (pages in rotation, rotation time, etc). Saved in a 48-hour cookie on local device. Access from the rotation page by clicking over the "Now" time in the title.</td></tr>

<!-- <tr><td ><a href='/caladmin.php' target='admin'>Calendar Admin page</a> </td><td >Ranger sets calendar events,  (password 'abcd')</td></tr> -->

<tr><td colspan='2' class='bg-yellow'>Developer Functions</td></tr>
<tr><td ><a href='/set_properties.php' target='_blank'>Reset properties.json</a> </td><td >gets coordinates, zones, and other data from weather.gov for significant sites. (only used for reference, not live).</td></tr>
<tr><td ><a href='/refresh.php' target='logs'>Refresh all caches</a></td><td > Updates all the external data (weather, airq, alerts); should run automatically every hour or so. This is an emergency tool.</td></tr>

<tr><td ><a href='/logview.php' target='_blank'>View Logs.</td>
	<td>Displays list of log records.</td></tr>

</table>


<?php echo $Plates->render ('end'); ?>
