<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\DisplayManager;

	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];


//END START


use DigitalMx\jotr\LogView as View;

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Log View',
	]);

echo $Plates->render('head',$meta);
echo $Plates->render('body',$meta);
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

