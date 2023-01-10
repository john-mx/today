<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START


	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;
	use DigitalMx\jotr\Calendar;

	$Plates = $container['Plates'];
	
	$Today = $container['Today'];


//u\echor ($_SESSION);
//END START


$y=$Today->load_cache('admin');
$admin['rotate'] = $y['rotate'] ?: ['today'];
$local = $_SESSION['local'] ?? [];
//u\echor($local,'local');
$rotate = ($local)?$local['rotate'] :$admin['rotate'] ;
$rdelay = ($local)?$local['rdelay']: $y['rdelay'];

// u\echor($rotate,'rotate',STOP);

$q = trim($_SERVER['QUERY_STRING']);

$qs = (!$q || ($q=='snap') )? 'snap' : '';

$meta=array(
	'qs' => $qs,
	'page' => basename(__FILE__),
	'subtitle' => '',
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
