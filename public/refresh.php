<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\CacheSettings as CS;
use DigitalMx\jotr\Utilities as U;
#ini_set('display_errors', 1);

//BEGIN START
	require_once  './init.php';


	$CM = $container['CacheManager'];


//END START
?>

<html><head>
<title>Refresh Caches</title>
<script>
function getCache(cs){
	window.location = '/refresh.php?'+cs;
	return true;
}
</script>

</head>
<body>

<?php
	$clist = CS::getCacheList(); sort ($clist);
	$coptions = U::buildOptions($clist);


if (empty($qs = $_SERVER['QUERY_STRING'])){
	show_instructions($coptions);
	exit;
} elseif ($qs == 'all'){
	echo "Starting all cache refresh, normal timing." . BR;
	$CM->refreshAllCaches();
} elseif ( $qs == 'force_all') {
	echo "Starting all cache refresh, forced" . BR;
	$CM->refreshAllCaches(true);
} elseif (in_array($qs,$clist)){
	echo "Storing force refresh on cache $qs." . BR;
	$CM->refreshCache($qs,true);
} else {
	echo "Error: $qs cache not found.";
}

echo "Done<br/><hr><br/>";
show_instructions($coptions);
function show_instructions($coptions){
	echo <<<EOT
<h3>Cache Refresh</h3>
<p>Caches are normally updated periodically through cron (automatic).  This page allows you to refresh when cron is not running or some problem is encountered.</p>
<p>Each cache has a specific lifetime, ranging from 20 minutes to 4 hours.  Normal cache refresh only updates caches that have expired ("stale").  The Force option causes the cache to be refreshed regardless of its lifetime setting.</p>
<p>
<a href='/refresh.php?all'>Refresh All </a>Refresh all caches that are due for an update.
</p>

<p>
<a href='/refresh.php?force_all'>Force All</a> Refresh all caches regardless of lifetime
</p>

<p>
Select cache to force refresh that cache only.:<br>
Force Refresh <select name='cache' id ='cselect' onChange='getCache(this.value)'>$coptions</select>
</p>

<p><a href='/pages.php'>Click to return to page list</a>
</p>
<p><b>Other Data</b></p>
<p><a href='/cga.php?rebuild'>Click</a> to rebuild campground attribute data file</p>

<p><a href='/set_properties.php' target='_blank' >Refresh properties </a>Gets coordinates, zones, and other data from weather.gov for significant sites. (only used for reference, not live).</p>

EOT;

}

?>


</body></html>

