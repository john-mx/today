<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheManager as CM;

ini_set('display_errors', 1);

//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	//
//
//
	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
	$Cal = $container['Calendar'];
	$CM = $container['CacheManager'];
	$PM = $container['PageManager'];


$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'About Today in the Park',
	]);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);

//END START


$Cal->convert_nps();

$nc = $CM->loadCache('mergecal');
//$nc = $CM->loadCache('calendar');

	$mc['events'] = $Cal->filter_events($nc['events'],4);
	U::echor($mc,'new filtered');
	echo $Plates->render('calendar',['calendar'=>$mc]);
