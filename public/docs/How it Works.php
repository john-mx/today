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
	'title' => 'How It Works',

	]);


echo $Plates->render('head',$meta);

echo $Plates->render('title',$meta);
//END START

?>
<h3>How it works</h3>
<p>The site works by periodically (like every 1/2 hour) querying various web resources to get updated information on weather, alerts, air quality, etc.  This information is stored (cached) until the next update.  </p>
	When a web page is requested, the stored information is
	retrieved and compiled into the form needed for display,
	and then sent to a template that exists for each web page or piece of a web page.
	The template does the final formatting for displaying the page.
</p>
<p>CacheManager runs the refresh operations, stores caches, and retrieves caches.  No other page touches the caches.</p>
<p>DisplayManager retrieves data from CacheManger, formats it for display.  It may utilize another class like Calendar or Camps.
</p>
<p>All data is displayed using Templates in directory plates/.  </p>
<p>Pages start out with
	<ul><li>namespace:DigitalMx\jotr;
	<li>set aliases for Utility and Definitions class
	<div class='code'>use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
</div>
	<li>Require init.php - starts session and calls config/Initialize
	<div class='code'>//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';
</div>
		<ul><li>Initialize sets constants and paths
		<li>Initialize runs services, which creates a container for all classes.
		</ul></li>
	<li>sets a "meta" array with page title and location, and other properties that may be needed in head areas, like rotate parameters
	<div class='code'>$meta=array('meta'=>[
	 &nbsp;&nbsp;&nbsp;'file' => basename(__FILE__),
	  &nbsp;&nbsp;&nbsp;'title' => 'How It Works',
	]);</div>
	basename is used to display file name in the browser's title or tab heading.
	<li>echos template head to create &lt;head&gt; content
	<br />echos template titles to create &lt; body&gt; tag and titles.
	<div class='code'>echo $Plates->render('head',$meta);
	echo $Plates->render('title',$meta);
</div>
This will be replaced by a PageManager class, when I get round toit.
	<li>php/html from here on out, often something like<br>
	<ul><li>Get an array of data from the Display manager
	<li>Send the data to a template to render it</ul>
	<div class='code'>$z = DisplayManager('prepare_topic_weather');
		echo Plates->render('weather',$z);
		</div>
</ul>

<h3>Web Sites</h3>
<p>There are 4 web sites used for this site (see <a href='Sites and Security.php'>Sites and Security</a> for details).
<dl>
<dt>Live</dt>
<dd>The live site, accessed at https://jotr.digitalmx.com
<dt>Beta</dt>
<dd>The "next" version of the live site.  Has its own data caches (weather, etc.) and admin settings, because structure may be different from current live. For testing before release.
<dt>Livex</td>
<dd>An exact copy of the live site, but with it's own local settings (ranger admin, calendar, campgrounds).  This is for people to learn how to use the site or try something out.
<dt>Dev</td>
<dd>For developer test purposes only.  Password protected.  For testing work in process.
</dl>
<p>
Local caches are the ones controlled by local admin settings: calendar, camps, ranger admin.  Each site has its own set of local caches.  External caches are caches refreshed by api to another place, like weather.gov.
</p>


<h3>Css</h3>
<p>All sites use the css file main.css.  It contains media rules for print which lowers font size and sets margins.  The rotate page adds an additional file tv.css.  This uses media rules to increase the root type size based on screen width, and changes the widely used h3 tag from left justified to center and underline.</p>
<p>Uses google font Rubik everywhere.  san-serif fallback.
<p>The "today.php" page is designed to be printed, so there are css pagebreaks between pages.  The "rotate.php" page is divided into sections also, and a javascript is used to rotate visibility among the desired sections.</p>

<h3>Development Process</h3>
Branch live is always the code on remote live and jotrx.
Branch work is branched from live and is the main working copy
Feature branches are branched from work
<ol>
<li><b>Develop</b><ol>
	<li>Development occurs on developers machine in repo work or  branches derived from work and merged back in.
	<li> Keep dev branches rebased to work
	<li>For testing, push dev &lt;dev branch&gt;:dev
	</ol></li>
<li><b>Beta Test</b><ol>
	<li>switch work,
	<li> merge dev and commit
	<li> push beta (work branch)
	<li> make sure it works
	</ol></li>
<li><b>Live</b><ol>
	<li>switch to live,
	<li> merge --squash work
	<li> change tag
	<li>Commit -m "what's new" to set version/build in data/version
	<li> push live to live
	<li> push live to jotrx

	</ol></li>
</ol>
