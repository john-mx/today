<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	$Plates = $container['Plates'];
	$Admin = $container['Admin'];
	$DM = $container['DisplayManager'];
	$Cal = $container['Calendar'];


$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Calendar Admin',
	]);

if (!$container['Login']->checklevel(basename(__FILE__))) exit;


///Utilities::echor($_POST,'post');

if (!empty($_POST) ) {
//		U::echor($_POST, 'POST');
		$Cal->post_calendar($_POST);
	echo "<script>location.reload();</script>";
		exit;


}
	echo $Plates->render('head',$meta);
	echo "<script src='/js/clearupdate.js'></script>";
	echo $Plates->render('title',$meta);

$y = $Cal-> prepare_admin_calendar();

echo "<form method=post>";
// $merge = $y['calendar'];
// $cal = $CM->loadCache('calendar');
// $npscal = $CM->loadCache('npscal')['npscal'];
// $merge['events'] = array_merge($cal['events'],$npscal);

// 	U::echor($merge,'merged admin');

echo $Plates->render('cal-admin',['calendar'=>$y]);
echo "</form>";




