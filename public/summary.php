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

$topics['calendar']['events'] = Calendar::filter_events($topics['calendar']['events'],1);

// U::echor($topics['calendar']);

//END START




$qs = '';
// using "Today' as title prevents it from re-appearing on the today page.

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => TODAY,
	'rotation' => [],
	]);


	echo $Plates->render ('head',$meta);
	echo <<<EOT
<style>
	@media print {
		table tr td {font-size:1.5rem;}
		table tr th{font-size:1.5rem;}
		}


	.no-print {display:none;}
	.head .title, .head .title>h2 {text-align:left;}

	</style>
EOT;
	echo $Plates->render ('title_nps',$meta);


	echo $Plates -> render('summary',array_merge($meta,$topics)) ;
