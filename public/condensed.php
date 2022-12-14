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

$qs = $_SERVER['QUERY_STRING'] ?? '';


$y = $Today->prepare_topics ();
//u\echor($y,'y',STOP);
$meta=array(
	'pcode' => $qs,
	'title'=>'Today Condensed',
	'target'=> $y['target']?? '',
//	'pithy'=> $y['pithy'] ?? '',
	'extra' => "<style>body{font-size:24pt;auto;width:100%}</style>",

	);

	echo $Plates->render ('start',$meta);
;
// using "Today' as title prevents it from re-appearing on the today page.

	echo $Plates -> render('condensed',['data'=>$y,'pcode'=>$qs]) ;
