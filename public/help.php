<?php
namespace DigitalMx\jotr;

//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

//	use DigitalMx\jotr\Utilities as U;
// 	use DigitalMx as u;
// 	use DigitalMx\jotr\Refresh;
// 	use DigitalMx\jotr\Definitions as Defs;


	$Plates = $container['Plates'];
// 	
// 	$Today = $container['Today'];
// 	$Cal = $container['Calendar'];

$q =  $_SERVER['QUERY_STRING'] ?? '';
$meta = array(

	'page' => basename(__FILE__),
	'subtitle' => 'Help',
	'extra' => "",

);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);
//END START

echo $Plates->render('help/' . $q);

?>
