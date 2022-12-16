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
<p>Project is under development.  Report errors and request changes to <a href='mailto:john@digitalmx.com'>John Springer &lt;john@digitalmx.com&gt;</a></p>


<ol>
<li> Local data, such as park alerts, closures, and fire level, are entered manually by an admin using the admin.php page.
<li> External data is collected every few hours from a number of places that report information like weather, air quality, astronomical data, and more. This date is stored on the site.
<li> The "today" page is built by combining all this data in a user-friendly format.
<li>It can be accessed as an ordinary web page, a scrolling page for use on a TV monitor, or a form set up for printing (page breaks, etc). The today page can be retrieved with a style code by appending ?style_name.
<li>The "open campsites" will be obtained live from rec.gov.
</ol>

<h4>Pages</h4>
<table class='index'>
<tr><td >URL</td><td >Result</td></tr>
<tr class='bg-yellow'><td>'Today' Pages</td><td>add '?snap' or '?scroll' to cycle through sections</tr>

<tr><td ><a href="/today.php" target='today'>/today.php</a>
</td><td >Regular web page with all data.  See note about printing.</td></tr>
<tr><td><a href="/condensed.php" target='condensed'>/condensed.php</a><td>Large type version with minimal information. Designed for snap animation on tv monitor. </td></tr>
<tr><td ><a href="/summary.php" target='summary'>/summary.php</a>
</td><td >One page summary of today's conditions. </td></tr>


<tr><td colspan='2'>--------- animated -----------</td></tr>
<tr><td ><a href="/today.php?scroll" target='scroll'>/today.php?scroll</a>
</td><td >(not recommended) Page slowly scrolls to the bottom, then restarts</td></tr>
<tr><td ><a href="/today.php?snap" target='snap'>/today.php?snap</a>
</td><td >Page displays a section for 10 seconds, then next section, and so on, before starting over.</td></tr>
<tr><td><a href="/condensed.php?snap" target='snap'>/condensed.php?snap</a><td> (reommended) Large type version with minimal information</td></tr>

<tr><td colspan='2' class='bg-yellow'>Ranger Admin Pages</td></tr>

<tr><td ><a href='/admin.php' target='admin'>Admin page</a> </td><td >Ranger sets things like alerts, closures, campground status, calendar events, etc.  (password 'abcd')</td></tr>

<tr><td colspan='2' class='bg-yellow'>Developer Functions</td></tr>
<tr><td ><a href='/set_properties.php' target='_blank'>Reset properties.json</a> </td><td >gets coordinates, zones, and other data from weather.gov for significant sites. (only used for reference, not live).</td></tr>
<tr><td ><a href='/cron_update.php' target='logs'>Refresh all caches</a></td><td > Updates all the external data (weather, airq, alerts); should run automatically every few hours.</td></tr>

<tr><td ><a href='/logview.php' target='_blank'>View Logs.</td>
	<td>Displays list of log records.</td></tr>

</table>
<h3>Printing</h3>
<p>
The page "today.php" can be printed from a web browser.
</p>
 <p>
 Printing will <ul>
 <li>change the margins to 0.5" on 8.5x11 pages
 <li> reduce the type size,
 <li> removed the "Current temperature" entry.
 </ul>
It should fit on three pages each with room for expanding content (alerts on page 1; calendar and weather on page 2; campgrounds on page 3; fees on page 4). Note that Fees doesn't change.
</p>
<p>If printed to a PDF, then the pdf can easily be distributed electronically</p>

<?php echo $Plates->render ('end'); ?>
