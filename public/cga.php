<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheManager as CM;
use DigitalMx\jotr\LocationSettings as LS;
use DigitalMx\jotr\Camps;

ini_set('display_errors', 1);

//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	//
//
//
	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
	$Cal = $container['Calendar'];
	$CM = $container['CacheManager'];
	$PM = $container['PageManager'];
	$Camps = $container['Camps'];




$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Campsite Attributes',
	]);

echo $Plates->render('head',$meta);
echo <<<EOT
<script>
function getCg(cs){
	window.location = '/cga.php?'+cs;
	return true;
}
</script>
EOT;

echo $Plates->render('body',$meta);

//END START
	// list all campgrounds that have attribute data on rec.gov
	$clist = ['ic','jr','br','cw','ry','sp'];
	foreach ( $clist as $cg){
		$cgs[$cg] = LS::getLocName($cg);
	}
	asort ($cgs);
// 		U::echor($clist,'clist',STOP);
	$coptions = U::buildOptions($cgs);

if (empty($qs = $_SERVER['QUERY_STRING'])){
	echo $Plates->render('title',$meta);
	show_instructions($coptions);
	exit;
} elseif ($qs=='rebuild'){
	// rebuild attribute file

	$cga = $Camps->rebuild_campsites($clist);
	show_instructions($coptions);
	exit;
} elseif (in_array($qs,$clist)){
	//report results
	$cga = $Camps->parseRecCampsite($qs);

	echo $Plates->render('cga',['cga'=>$cga,'loc'=>$qs]);
	exit;
} else {
	echo "Error: $qs campground not found.";
}


function show_instructions($coptions){
	echo <<<EOT
<h3>Campsite Attributes</h3>
<p>Retrieve a list of all the campsites in a campground, along
with key attributes:
<ul>
<li>Maximum length of vehicles
<li>Maximum number of people
<li>Maximum number of vehicles
</ul>
This data comes directly from recreation.gov, so is the official restriction on the campsite.
</p>


<p>
Select campground: <select name='cache' id ='cselect' onChange='getCg(this.value)'>$coptions</select>
</p>
<p>When printing, be sure to select Landscape orientation. There are up to 120 sites per page.</p>

<p><a href='/pages.php'>Click to return to page list</a>
<p><a href='/cga.php?rebuild'>Click</a> to rebuild attribute data file
<hr>
Additional site information may be available on this report.  Contact developer for info.  John Springer, john@digitalmx.com.
EOT;
}

?>

}

