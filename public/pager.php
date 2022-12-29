<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;
	use DigitalMx\jotr\Calendar as Cal;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];
	$Cal = new Cal();



$page = $_SERVER['QUERY_STRING'];

$y=$Today->build_topics();
//u\echor ($y,'topics');

$extra = '';

switch ($page) {

	case 'fees':
		$subtitle = 'Fee Schedule';
		$plate= 'fees';
		$z = $y;
		break;
	case 'fees-condensed':
		$subtitle = 'Fees (Condensed)';
		$plate='fees-condensed';
		$z=$y;
		break;


	case 'events':
		$subtitle = "Upcoming Events";
		$plate = 'calendar';

		$z['calendar'] = $Cal->filter_calendar($y['calendar'],3);
		break;

	case 'weather':
		$subtitle = "Weather";
		$plate = 'weather';

		$z['wgov'] = $y['wgov'];
		break;



	default:
		die ("Page not recognized: $page");
}

$meta=array(
	//'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => $subtitle,
	'extra' => $extra,

	);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);
echo $Plates->render($plate,$z);
