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
		html {
		font-size: 50%;
			}
		.head .div {font-size:0.8em;}
		table tr td {font-size:1.5rem;}
		table tr th{font-size:1.5rem;}

		@page {
			size: 8.5in 11in;
			margin: 0.3in;
			padding 0;

		}
	}
	</style>
EOT;
	echo $Plates->render ('title',$meta);


	echo $Plates -> render('summary',array_merge($meta,$topics)) ;
