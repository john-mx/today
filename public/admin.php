<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


/*
	today admin page

	first checks for login level.
	If fails, then shows login screen.  Logging in returns to this screen.

*/

#ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	use DigitalMx\jotr\Today;
	use DigitalMx\jotr\Utilities as J;


	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
	$Login = $container['Login'];
	$Cal = $container['Calendar'];
	$Admin = $container['Admin'];


//END START
$topics = $DM->build_topics();



$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Site Admin',
	'extra' => "<script src='/js/clearupdate.js'></script>",
	);

//Utilities::echor($meta,'meta',STOP);
if (isset($_POST['pw']) ) {// is login
	$Login->set_pwl($_POST['pw']);
}


$Login->check_pw(5);

//Utilities::echor($_POST,'post');

if (!empty($_POST) && !isset($_POST['pw'])) {
//		U::echor($_POST, 'POST',STOP);
		$Admin->post_admin($_POST);
		echo "<script>location.reload();</script>";
		exit;

} else {

	echo $Plates->render('head',$meta);
	echo "<body>";
	echo $Plates->render('title',$meta);

	$y = $Admin-> prepare_admin();
//	 Utilities::echor($y, 'prepared admin');
	echo $Plates->render('admin',$y);


	exit;
}



####################


function login($pw,$DM,$Plates,$Login) {


	if (strlen($pw)<4){
		echo "Error: password not correct (1)";
		show_login();
		echo "</body></html>" . NL;
		exit;
	}
	if (! $Login->set_pwlevel($pw)){
		echo "Error: password not recognized (2)";
		show_login();
		echo "</body></html>" . NL;
		exit;
	}
	show_admin($DM,$Plates);
}

function post_data($post){

	//Utilities::echor ($post,'post submityyted' , STOP);
	$Admin->post_admin($post);


}


