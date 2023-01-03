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
	$Cal = new Calendar();


//END START




$qs = '';
// using "Today' as title prevents it from re-appearing on the today page.
$meta=array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => '',
	'extra' =>'',

);


	echo $Plates->render ('head',$meta);
		echo $Plates->render ('title',$meta);

//	echo $Today->start_page('Today in the Park',$qs);

	echo $Plates -> render('summary') ;
