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

$z['calendar']['events'] = Calendar::filter_events($topics['calendar']['events'],1);
//U::echor($z);

//END START




$qs = '';
// using "Today' as title prevents it from re-appearing on the today page.

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => TODAY,
	'rotation' => [],
	]);


	echo $Plates->render ('head',$meta);

	echo $Plates->render ('title',$meta);


	echo $Plates -> render('summary',['calendar' => $z['calendar'],'uv'=>$topics['uv']]) ;
