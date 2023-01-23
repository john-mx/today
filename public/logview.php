<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];

	$Today = $container['Today'];


//END START


use DigitalMx\jotr\LogView as View;

$meta=array(
	//'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Log View',
	'extra' => "",

	);
echo $Plates->render('head',$meta);
echo "<body>";
echo $Plates->render('title',$meta);
//
$dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs';
 $view = new View($dir);
// // view log index
//
if (!empty($log=urldecode($_SERVER['QUERY_STRING']))){
	$view->show_log($log);
}
$view->list_logs();

