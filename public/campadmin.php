<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	$Plates = $container['Plates'];
	$Admin = $container['Admin'];
	$DM = $container['DisplayManager'];


$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Campground Admin',
	]);

if (!$container['Login']->checklevel(basename(__FILE__))) exit;


//Utilities::echor($_POST,'post');

if (!empty($_POST) ) {
//		U::echor($_POST, 'POST',STOP);
		$Admin->post_admin($_POST);
		echo "<script>location.reload();</script>";
		exit;


}
$y = $Admin-> prepare_admin();
	echo $Plates->render('head',$meta);
	echo "<script src='/js/clearupdate.js'></script>";
	echo $Plates->render('body',$meta);
	echo $Plates->render('title',$meta);

echo "<form method=post>";
echo $Plates->render('camp-admin',$y);
echo "</form>";




