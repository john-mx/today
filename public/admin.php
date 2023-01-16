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

	$Today = $container['Today'];
	$Login = $container['Login'];
	$Cal = $container['Calendar'];
	$Admin = $container['Admin'];


//END START




$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Site Admin',
	'extra' => "<script>src='/js/clearupdate.js'</script>",
	);

//Utilities::echor($meta,'meta',STOP);
if (isset($_POST['pw']) ) {// is login
	$Login->set_pwl($_POST['pw']);
}


$Login->check_pw(5);

//Utilities::echor($_POST,'post');

if (!empty($_POST) && !isset($_POST['pw'])) {
	//	echo "Posting Data";
		post_data ($_POST,$Today);
		echo "<script>location.reload();</script>";
		exit;

} else {

// get calendar


	echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);

		$y = $Admin-> prepare_admin();
 Utilities::echor($y, 'prepared admin', STOP);
		echo $Plates->render('admin',$y);


	exit;
}



####################


function login($pw,$Today,$Plates,$Login) {


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
	show_admin($Today,$Plates);
}

function post_data($post,$Today){

	//Utilities::echor ($post,'post submityyted' , STOP);
	$Admin->post_admin($post);


}


