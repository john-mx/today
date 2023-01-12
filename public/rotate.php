<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START


	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\Today;
	use DigitalMx\jotr\Calendar;

	$Plates = $container['Plates'];

	$Today = $container['Today'];


//Utilities::echor ($_SESSION);
//END START


$y=$Today->load_cache('admin');
$admin['rotate'] = $y['rotate'] ?: ['today'];
$local = $_SESSION['local'] ?? [];
//Utilities::echor($local,'local');

$rotate = (isset($local['rotate']))?$local['rotate'] :$admin['rotate'] ;
$rdelay = (isset($local['rdelay']))?$local['rdelay']: $y['rdelay'];


// Utilities::echor($rotate,'rotate',STOP);

$q = trim($_SERVER['QUERY_STRING']);

$qs = (!$q || ($q=='snap') )? 'snap' : '';

$meta=array(
	'qs' => $qs,
	'page' => basename(__FILE__),
	'subtitle' => TODAY,
	'extra' => "<link rel='stylesheet' href='/css/tv.css'>",
	'rotate' => $rotate,
	'rdelay' => $rdelay,
	'sunset' => $Today->sunset,
	'local_site' => $local['local_site'] ?? '',

	);

	echo $Plates->render ('head',$meta);
	echo $Plates->render('title',$meta);

;
// using "Today' as title prevents it from re-appearing on the today page.

	echo $Plates -> render('condensed',$meta) ;
