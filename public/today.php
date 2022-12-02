<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];


//END START

$qs = $_SERVER['QUERY_STRING'] ?? '';


$y = $Today->prepare_topics ();
#u\echor($y);

// using "Today' as title prevents it from re-appearing on the today page.
$meta=array('pcode' => $qs,'title'=>'Today');
	echo $Plates->render ('start',['meta'=>$meta]);

//	echo $Today->start_page('Today in the Park',$qs);
	echo $Plates -> render('today',['data'=>$y,'meta'=>$meta]) ;
// 	file_put_contents(REPO_PATH .'/data/test.html',
// 		$Plates -> render('today',['data'=>$y]) );
//	if ($qs == 's')echo $Plates -> render ('scroll_script');
//	u\echor($y,'data to plate');
