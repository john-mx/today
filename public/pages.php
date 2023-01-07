<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require  'init.php';

	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	$Plates = $container['Plates'];
	$Defs = $container['Defs'];

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
<tr><td >URL</td><td >Result</td></tr>
<tr class='bg-yellow'><td>'Today' Pages</td><td>add '?snap' or '?scroll' to cycle through sections</tr>

<tr><td ><a href="/today.php" target='today'>/today.php</a>
</td><td >Regular web page with all data.  Does not include campsite availability. Publicaly accessible.</td></tr>

<tr><td ><a href="/summary.php" target='summary'>/summary.php</a>
</td><td >One page summary of today's conditions, alerts, and events. </td></tr>


<tr><td colspan='2'>--------- animated -----------<br>
("scroll" is smooth scroll through page.  "snap" switches like a slide show.)</td></tr>
<tr><td ><a href="/today.php?scroll" target='scroll'>/today.php?scroll</a>
</td><td >(not recommended) Page slowly scrolls to the bottom, then restarts</td></tr>
<tr><td><a href="/rotate.php" target='snap'>/rotate.php</a><td> (reommended) Large type version designed for TV. Rotates through pages, changing every 15 seconds.  You can choose which pages (or just one page) appear in the rotation using the admin page. Automatically uses "snap" animation unless url ends "?nosnap" </td></tr>

<tr><td colspan='2' class='bg-yellow'>Ranger Admin Pages</td></tr>

<tr><td ><a href='/admin.php' target='admin'>Admin page</a> </td><td >Ranger sets things like alerts, closures, campground status, calendar events, etc.</td></tr>

<!-- <tr><td ><a href='/caladmin.php' target='admin'>Calendar Admin page</a> </td><td >Ranger sets calendar events,  (password 'abcd')</td></tr> -->

<tr><td colspan='2' class='bg-yellow'>Developer Functions</td></tr>
<tr><td ><a href='/set_properties.php' target='_blank'>Reset properties.json</a> </td><td >gets coordinates, zones, and other data from weather.gov for significant sites. (only used for reference, not live).</td></tr>
<tr><td ><a href='/refresh.php' target='logs'>Refresh all caches</a></td><td > Updates all the external data (weather, airq, alerts); should run automatically every hour or so. This is an emergency tool.</td></tr>

<tr><td ><a href='/logview.php' target='_blank'>View Logs.</td>
	<td>Displays list of log records.</td></tr>

</table>


<?php echo $Plates->render ('end'); ?>
