<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

//	use DigitalMx\jotr\Utilities as U;
//
// 	use DigitalMx\jotr\Refresh;
//



	$Plates = $container['Plates'];

$q =  $_SERVER['QUERY_STRING'] ?? '';
$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Help',
	]);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);
//END START

echo $Plates->render('help/' . $q);

?>
