<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START


	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\Calendar;

	$Plates = $container['Plates'];
	$CM = $container['CacheManager'];
	$DM = $container['DisplayManager'];


//Utilities::echor ($_SESSION);
//END START
$topics = $DM->build_topics();

$y=$CM->loadCache('admin');
$admin['rotate'] = $y['rotate'] ?: ['today'];
$local = $_SESSION['local'] ?? [];
//Utilities::echor($local,'local');

$rotate = (isset($local['rotate']))?$local['rotate'] :$admin['rotate'] ;
$rdelay = (isset($local['rdelay']))?$local['rdelay']: $y['rdelay'];



// Utilities::echor($rotate,'rotate',STOP);

$q = trim($_SERVER['QUERY_STRING']);

$qs = (!$q || ($q=='snap') )? 'snap' : '';
$tvcss=U::addTimestamp('/css/tv.css');
$meta=array(
	'qs' => $qs,
	'page' => basename(__FILE__),
	'subtitle' => TODAY,
	'extra' => '',
	'extra_style' => 'tv.css',
	'rotate' => $rotate,
	'rdelay' => $rdelay,
	'sunset' => $DM->sunset,


	);

	echo $Plates->render ('head',$meta);
?>
<style type='text/css'>
html { font-size:130%; /*20 pt */
}
 body {
 	font-weight:500;
	padding:0.5em;;
	width:100vm;
	padding-top:0;
/* 	max-width:960px; */

	}

 #content {

	min-width:1400px;
	padding-left:1em;
	padding-right:1em;
}

 #titles {
	min-width:1400px;
	padding:1em;



}


.page {
	transform-origin:top center;
}
table tr td {font-size:1.6rem;}
table th {font-size: 1.6rem;}

h3 {
	margin-top:1rem;
	text-align:center;
	text-decoration:underline;
}
</style>


<?php
echo "<body id='rotator' onLoad=load_snap()>";

echo $Plates->render('title',$meta);


	echo $Plates -> render('condensed',$meta) ;
?>
