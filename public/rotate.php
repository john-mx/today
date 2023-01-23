<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START


	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\Today;
	use DigitalMx\jotr\Calendar;

	$Plates = $container['Plates'];
	$CM = $container['CacheManager'];
	$Today = $container['Today'];


//Utilities::echor ($_SESSION);
//END START
$topics = $Today->build_topics();

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
	'sunset' => $Today->sunset,
	'local_site' => $local['local_site'] ?? '',

	);

	echo $Plates->render ('head',$meta);
?>
<style type='text/css'>
:root {
	font-family:Rubik,san-serif;
	font-size:28pt;
	font-weight:500;
}
 body {
	padding:0;
	width:100%;
	padding-top:0;
/* 	max-width:960px; */

	}

 #content {
/* 	width:100%; */
	min-width:1400px;
	padding-left:1rem;
	padding-right:1rem;
}

 #head {
	min-width:1400px;
	padding:0;
}

 .page {
	transform-origin:top center;
}
table tr td {font-size:28pt;}
 h3 { font-size: 1.25rem;
	font-weight:bold;
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
