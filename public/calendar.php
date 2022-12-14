<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;


	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];
	$Cal = $container['Calendar'];

	echo $Plates->render('start',['title'=>'Calendar']);

//END START


	$c = $Cal->load_cache();
//u\echor($c,'c.json',STOP);

	$calendar = $Cal->filter_calendar($c,4);
//u\echor($calendar,'filtered cal');
	$platedata = array('calendar'=>$calendar);
	echo $Plates->render('calendar',$platedata);
	exit;


