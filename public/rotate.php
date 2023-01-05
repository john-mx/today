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
	$Defs = $container['Defs'];
	$Today = $container['Today'];

//END START


$y=$Today->load_cache('admin');
$rotate = $y['rotate'] ?? '';

// u\echor($rotate,'rotate',STOP);
$q = $_SERVER['QUERY_STRING'];
if (empty($q)) {$qs='';}
else {$qs = 'snap';}
$meta=array(
	'qs' => $qs,
	'page' => basename(__FILE__),
	'subtitle' => '',
	'extra' => "<link rel='stylesheet' href='/css/tv.css'>",
	'rotate' => $rotate,
	'rdelay' => $y['rdelay'],
	'sunset' => $Today->sunset,

	);

	echo $Plates->render ('head',$meta);
	echo $Plates->render('title',$meta);

;
// using "Today' as title prevents it from re-appearing on the today page.

	echo $Plates -> render('condensed',$meta) ;
