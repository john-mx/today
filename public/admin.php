<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


/*
	today admin page

	first checks for login level.
	If fails, then shows login screen.  Logging in returns to this screen.

*/

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];

	$Cal = $container['Calendar'];
	$Admin = $container['Admin'];


//END START



$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Main Site Admin',
	]);


//Utilities::echor($meta,'meta',STOP);
// if (isset($_POST['pw']) ) {// is login
// 	$Login->set_pwl($_POST['pw']);
// }


//Utilities::echor($_POST,'post');

if (!empty($_POST))
{
//		U::echor($_POST, 'POST',STOP);
		$Admin->post_admin($_POST);
		//echo "<script>location.reload();</script>";


}

	echo $Plates->render('head',$meta);
	echo "<script src='/js/clearupdate.js'></script>";
	echo $Plates->render('title',$meta);

	$y = $Admin-> prepare_admin();
//	 Utilities::echor($y, 'prepared admin');
	echo "<div class='content'><form method='post'>";
	echo $Plates->render('ranger',$y);
	echo $Plates->render('admin',$y);
	echo "</form></div>";

	exit;




####################
