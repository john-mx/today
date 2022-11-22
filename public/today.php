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


$y = $Today->prepare_today ();
#u\echor($y);
	// set forecee to true to force all cahces to rebuild now, instead of on schedule
echo "rendering...";
	echo $Today->start_page();
	echo $Plates -> render('main',$y);

