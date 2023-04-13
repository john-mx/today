<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\DisplayManager as DM;



//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
$topics = $DM->build_topics();

//END START

//Utilities::echor($y,'y',STOP);

// set up page vars in meta
$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => TODAY,
	'rotation' => [],
	'sunset' => $DM->getSunset(),
	]);

echo $Plates->render ('head',$meta);
	// still in head.  Put extra stuff here.
echo $Plates->render('body',$meta);
$meta['title_bar'] = $Plates->render('title',$meta);
//echo $meta['title_bar'];
//echo $Plates->render('title',$meta);

echo $Plates -> render('today',$meta) ;

