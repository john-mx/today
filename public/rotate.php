<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];


//END START




$y = $Today->prepare_topics ();
//u\echor($y,'y',STOP);

$rotate = $y['rotate'] ?? '';


$meta=array(
	'qs' =>  'snap',
	'page' => basename(__FILE__),
	'subtitle' => '',
	'extra' => "<link rel='stylesheet' href='/css/tv.css'>",
	'rotate' => $rotate,

	);

	echo $Plates->render ('head',$meta);
	echo $Plates->render('title',$meta);

;
// using "Today' as title prevents it from re-appearing on the today page.

	echo $Plates -> render('condensed',['data'=>array_merge($y,$meta)]) ;
