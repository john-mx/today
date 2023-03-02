<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



#ini_set('display_errors', 1)

//BEGIN START
	require_once 'init.php';
//echo "At " . basename(__FILE__) . " [". __LINE__ ."]" . BR;
	$Plates = $container['Plates'];
	$DM = $container['DisplayManager'];
	$Login = $container['Login'];

//END START
//if (!$Login->checklevel(basename(__FILE__))) exit;
$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Index of Pages',
	]);

//echo basename(__FILE__) . " [". __LINE__ ."]" . BR;



echo $Plates->render('head',$meta);

echo $Plates->render('title',$meta);


echo $Plates->render('pages');

echo $Plates->render ('sig');
