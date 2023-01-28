<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';





	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
$topics = $DM->build_topics();

//END START

$qs = $_SERVER['QUERY_STRING'] ?? '';



//Utilities::echor($y,'y',STOP);

// using "Today' as title prevents it from re-appearing on the today page.
$meta=array(
	'qs' => $qs,
	'page' => basename(__FILE__),
	'title' => TODAY,
	'pithy'=> $y['pithy'] ?? '',
	'sunset' => $DM->sunset,
	'local_site' => $local['local_site'] ?? '',
	);
	$bodycode = ($qs == 'scroll')? "onLoad='pageScroll()'" : '';
	echo $Plates->render ('head',$meta);
	echo "<body $bodycode>";
	echo $Plates->render('title',$meta);

//	echo $DM->start_page('Today in the Park',$qs);

	echo $Plates -> render('today',$meta) ;

// 	file_put_contents(REPO_PATH .'/data/test.html',
// 		$Plates -> render('today',['data'=>$y]) );
//	if ($qs == 's')echo $Plates -> render ('scroll_script');
//	Utilities::echor($y,'data to plate');
