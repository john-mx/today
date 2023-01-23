<?php

namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];

	$Today = $container['Today'];


//END START



// using "Today' as title prevents it from re-appearing on the today page.
$meta=array(
	'qs' =>  $_SERVER['QUERY_STRING'],
	'subtitle'=>'Marc\'s Page',
	'pithy'=> $y['pithy'] ?? '',
	'page' => basename(__FILE__),

	);

	echo $Plates->render ('head',$meta);
	echo "<body>";
	echo $Plates->render('title',$meta);



$y = $Today->prepare_topics ();
//Utilities::echor($y,'y');

echo $Plates -> render('weather',['weather' => $y['wgov'] ]) ;

