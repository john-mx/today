<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

ini_set('display_errors', 1);


//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';


//
//
	$Plates = $container['Plates'];

	$Today = $container['Today'];
	$Cal = $container['Calendar'];
	$CM = $container['CacheManager'];
	$DM = $container['DisplayManager'];

$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Test page',
	'extra' => "<script src='/js/show_time.js'></script>",

);
$DM->showLight();

//echo $Plates->render('head',$meta);
//echo $Plates->render('title',$meta);
//END START

// $CM->refreshCache('wgov');
//
// $y = $CM->loadCache('wgov');
// U::echor($y);
