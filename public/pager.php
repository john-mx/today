<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;


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
$title = TODAY;
switch ($page) {

	case 'sum2':
		$plate = 'summary2';
		$z=$DM->getTopics(['cal','air','uv','light','admin']);

//U::echor($y,'y');
		$z['calendar']['events'] = Calendar::filter_events($z['calendar']['events'],1);
		break;

	case 'events':
//		$subtitle = "Upcoming Events";
		$plate = 'calendar';
		$y=$DM->build_topic_calendar();

//  	U::echor($y['calendar'],'prefilter');
		$z['calendar']['events'] = Calendar::filter_events($y['calendar']['events'],3);
//  U::echor($z,'z in pager');
		break;

	case 'weather':
		//$subtitle = "Weather";
		$plate = 'weather';

		//$z['wgov'] = $y['weather'];
		break;
	case 'notices':
		//$subtitle = "Alerts, Notices, and Advice";
		$z=$y;
		$plate = 'alerts';
		$plate2 = 'notices';
		$plate3 = 'advice';
		break;

	case 'current':
		$z = array_merge(
			$DM->build_topic_current(),$DM->build_topic_uv()
		);
	//	U::echor($z);
		$plate = 'conditions';
		break;

	case 'campgrounds':
		//$subtitle = 'Campgrounds';
		$z=$y;
		$plate = 'campground';
		$plate2 = 'qr_camps';
		break;

	case 'camp-tv':
		//$subtitle = 'Campgrounds';
		$z=$y;
		$plate = 'campground-tv';

		break;

	case 'fees':
		//$subtitle = 'Fees';
		$z=['fees' => LS::getFees()];
		$plate = 'fees';
		$plate2 = 'qr_fees';
		break;

	case 'npscal':
		//$subtitle = "NPS Calendar";
		$plate='calendar';
		$z['calendar']['events'] = $CM->loadCache('npscal')['npscal'];
		U::echor($z['calendar']);
		break;

	default:
		die ("Page not recognized: $page");
}

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => $title,
	]);
$z=array_merge($z,$meta);

echo $Plates->render('head',$meta);
echo $Plates->render('body',$meta);
echo $Plates->render('title',$meta);
$z['meta'] = $meta;

echo $Plates->render($plate,$z);
if (!empty($plate2)) echo $Plates->render($plate2,$z);
if (!empty($plate3)) echo $Plates->render($plate3,$z);

