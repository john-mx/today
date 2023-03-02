<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;

use \DigitalMx\jotr\Api;
use \DigitalMx\jotr\CacheSettings as CS;
use DigitalMx\jotr\CacheManager as CM;
use DigitalMx\jotr\Calendar as Cal;


//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	$Plates = $container['Plates'];
	$CM = $container['CacheManager'];
	$DM = $container['DisplayManager'];

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Calendar using NPS data',
	]);

echo $Plates->render('head',$meta);

echo $Plates->render('title',$meta);



	$r = $DM->build_topic_npscal();
	//$r = Cal::filter_calendar($r,1);
//	U::echor($r);

	echo  $Plates->render('calendar',['calendar' => $r]);
}
