<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';






	$Plates = $container['Plates'];


	$CM = $container['CacheManager'];
	$DM = $container['DisplayManager'];




$page = $_SERVER['QUERY_STRING'];

//$CM->refreshCache('wgov');

$y=$DM->build_topics();
//Utilities::echor ($y,'topics');

$extra = '';
$plate2 = '';
$z=[];
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

		$z['calendar'] = Calendar::filter_calendar($y['calendar'],3);
		break;

	case 'weather':
		$subtitle = "Weather";
		$plate = 'weather';

		//$z['wgov'] = $y['weather'];
		break;
	case 'notices':
		$subtitle = "Alerts and Notices";
		$z=$y;
		$plate = 'alerts';
		$plate2 = 'notices';
		break;

	case 'campgrounds':
		$subtitle = 'Campgrounds';
		$z=$y;
		$plate = 'campground';
		break;

	case 'fees2':
		$subtitle = 'Fees 2';
		$z=['fees' => Defs::getFees()];
		$plate = 'fees2';
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
echo "<body>";
echo $Plates->render('title',$meta);
echo $Plates->render($plate,$z);
if ($plate2){
echo $Plates->render($plate2,$z);
}

