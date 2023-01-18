<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];

	$Today = $container['Today'];
$topics = $Today->build_topics();

//END START

$qs = $_SERVER['QUERY_STRING'] ?? '';



//Utilities::echor($y,'y',STOP);

// using "Today' as title prevents it from re-appearing on the today page.
$meta=array(
	'qs' => $qs,
	'page' => basename(__FILE__),
	'title' => TODAY,
	'pithy'=> $y['pithy'] ?? '',
	'sunset' => $Today->sunset,
	);

	echo $Plates->render ('head',$meta);
	echo $Plates->render('title',$meta);

//	echo $Today->start_page('Today in the Park',$qs);

	echo $Plates -> render('today',$meta) ;

// 	file_put_contents(REPO_PATH .'/data/test.html',
// 		$Plates -> render('today',['data'=>$y]) );
//	if ($qs == 's')echo $Plates -> render ('scroll_script');
//	Utilities::echor($y,'data to plate');
