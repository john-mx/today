<?php

?>
<p>Pages requiring a different login are grayed out. <a href="/logout.php?pages.php">Log Out and back In.</a></p>

<h4>Pages</h4>
<table class='index'>
<tr><td >Link</td><td >Result</td></tr>
<tr class='bg-yellow'><td colspan='2'>'Today in the Park' Pages</td><td></td></tr>

<tr><td ><a href="/today.php" target='today'>Today Page</a>
</td><td >Web site with all data on one web page. Can be printed (5 pages). </td></tr>

<tr><td ><a href="/summary.php" target='summary'>Summary</a>
</td><td >One page summary of today's conditions, alerts, and events. </td></tr>

<tr><td><a href="/rotate.php" target='rotate'>Rotate</a>
</td>
<td> (recommended) Large type version designed for TV. Rotates through topics, changing every 15 seconds.  (You can choose which pages (or just one page) appear in the rotation using the admin page. ) Uses "snap" animation unless url is "/rotate.php?nosnap" </td></tr>
<tr><td ><a href="/rotate.php?scroll" target='rotate'>Scroll</a>
</td><td >(not recommended) Rotate page (limited info, large type) scrolls to the bottom, then restarts</td></tr>



<tr class='bg-yellow'><td colspan='2'>Individual Topics </td></tr>
<tr><td><a href='/pager.php?weather' target='topic'>Weather</td><td>3-day forecast, Jumbo Rocks, Black Rock and Cottonwood</tr>
<tr><td><a href='/pager.php?fees' target='topic'>Fee Schedule</td><td>All fees and where to buy</td></tr>
<tr><td><a href='/pager.php?events' target='topic'>Calendar</td><td>Events for next 3 days</td></tr>
<!-- <tr><td><a href='/pager.php?npscal' target='topic'>NPS Calendar</td><td>Events from the nps.gov/jotr calendar</td></tr> -->

<tr><td><a href='/pager.php?notices' target='topic'>Notices</td><td>Advice, Alerts and Notices</tr>
<tr><td><a href='/pager.php?campgrounds' target='topic'>Campgrounds</td><td>Campground status, sites, and fees</tr>
<tr><td><a href='/pager.php?current' target='current'>Current Conditions</td><td>From LHRS</td></tr>

<tr><td colspan='2' class='bg-yellow'>Miscellaneous Pages</td></tr>
<tr><td><a href="/rotate.php?nosnap" target='snap'>No Rotate</a></td><td>All Rotation pages without the rotation.</td></tr>

<tr><td><a href="/cga.php">Campground Attributes</a></td>
	<td>List of campsites and attributes (max length, etc) for each campground. </td></tr>

<tr><td><a href="/docs/">Project Docs</a></td>
	<td>Documents about the site and status.</td></tr>

<tr class='bg-yellow'><td colspan='2'>General Information</td></tr>

<tr><td><a href='/'>Overview</a></td><td>Overview of the project</td></tr>

<tr><td><a href='about.php' target='about'>About</a></td><td>How it Works.</td></tr>
<tr><td><a href='setup.php' target='setup'>TV Setup</a></td>
	<td>Instructions for setting up display on a TV</td></tr>
<tr><td><a href='/structure.php' target='structure'>Structure</a></td><td>Structure of the system</td></tr>



<tr><td colspan='2' class='bg-yellow'>Admin Pages</td></tr>
<?php $thisclass = $this->hasAccessClass('campadmin.php');?>
<tr <?=$thisclass?> >
	<td ><a href='campadmin.php' target='admin' <?=$thisclass?> >Campground Admin </a></td><td>Set status and availability for campgrounds</td></tr>

<?php $thisclass = $this->hasAccessClass('caladmin.php');?>
<tr <?=$thisclass?> >
	<td ><a href='caladmin.php' target='admin' <?=$thisclass?> >Calendar Admin </a></td><td>Set Events</td></tr>

<?php $thisclass = $this->hasAccessClass('ranger.php');?>
<tr <?=$thisclass?> ><td><a href='ranger.php' target='admin' <?=$thisclass?> >Ranger Admin  </a></td><td>Sets alerts, notices, calendar events, etc. Includes campgrounds.</td></tr>

<?php $thisclass = $this->hasAccessClass('admin.php');?>
<tr <?=$thisclass?> ><td ><a href='/admin.php' target='admin' <?=$thisclass?> >Main Admin </a> </td><td >Ranger page, plus default rotation pages, alternate alert.</td></tr>

<tr><td ><a href='/local.php' target='local'>Local Settings</a> </td><td >Settings for local display (like this one, right here) Sets pages in rotation, rotation time, etc. Saved in a 48-hour cookie on local device. Access from the rotation page on the device by clicking over the "Now" time in the title.</td></tr>


<tr><td colspan='2' class='bg-yellow'>Developer Functions</td></tr>
<tr><td> <a href='/logout.php'>Log Out</a></td><td>Clears login from current session.</td></tr>
<?php if (!LIVE) : ?>
<?php $thisclass = $this->hasAccessClass('copy_live.php');?>
<tr <?=$thisclass?>><td> <a href='/copy_live.php' <?=$thisclass?> >Copy from Live </a></td><td>Copies data (camps, calendar, ranger admin) from live site to this site.</td></tr>
<?php endif; ?>

<?php $thisclass = $this->hasAccessClass('set_properties.php');?>
<tr <?=$thisclass?>><td ><a href='/set_properties.php' target='_blank' <?=$thisclass?> >Refresh properties </a> </td><td >gets coordinates, zones, and other data from weather.gov for significant sites. (only used for reference, not live).</td></tr>

<?php $thisclass = $this->hasAccessClass('session.php');?>
<tr <?=$thisclass?>><td> <a href='/session.php' <?=$thisclass?> >Show Session </a></td><td>Current $_SESSION</td></tr>

<!--
<tr><td><a href='http://jotrbeta.digitalmx.com/' target='_blank'>Beta site</a> </td><td>Go to the jotrbeta test site</td></tr>

 -->
<?php $thisclass = $this->hasAccessClass('refresh.php');?>
<tr <?=$thisclass?> ><td ><a href='/refresh.php' target='refresh' <?=$thisclass?> >Refresh caches </a></td><td > Updates all the external data (weather, airq, alerts); should run automatically every hour or so. This is an emergency tool. Can force updates of any or all caches regardless of age.</td></tr>

<?php $thisclass = $this->hasAccessClass('logview.php');?>
<tr <?=$thisclass?> ><td ><a href='/logview.php' target='_blank'>View Logs. </td>
	<td>Displays list of log records.  Click log to view last 40 records.</td></tr>

</table>
