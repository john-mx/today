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
#u\echor($y);

	echo $Today->start_page();
	echo $Plates -> render('today',['data'=>$y]);
//	u\echor($y,'data to plate');