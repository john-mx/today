<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	use DigitalMx\jotr\Calendar;

	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
$topics = $DM->build_topics();

//END START




$qs = '';
// using "Today' as title prevents it from re-appearing on the today page.
$meta=array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => TODAY,
	'extra' =>'',

);


	echo $Plates->render ('head',$meta);
	echo "<body>";
		echo $Plates->render ('title',$meta);


	echo $Plates -> render('summary') ;
