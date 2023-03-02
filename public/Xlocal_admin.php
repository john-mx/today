<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


/*
	local admin page

	first checks for login level.
	If fails, then shows login screen.  Logging in returns to this screen.

*/


#ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';




	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];

	$Today = $container['Today'];
	$Login = $container['Login'];
	$Cal = $container['Calendar'];


//END START
if (!$container['Login']->checklevel(basename(__file__))) exit;


$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Local Admin',
	]);


//Utilities::echor($meta,'meta',STOP);
if (isset($_POST['pw']) ) {// is login
	$Login->set_pwl($_POST['pw']);
}


$Login->check_pw(2);

//Utilities::echor($_POST,'post');

if (!empty($_POST) && !isset($_POST['pw'])) {
		post_data ($_POST,$Today);
		echo "<script>window.location.href='/local.php';</script>";
		exit;

} else {

	echo $Plates->render('head',$meta);
	echo "<body>";
echo $Plates->render('title',$meta);

		$y = $Today-> prepare_admin();
// Utilities::echor($y);
		echo $Plates->render('local',$y);
echo "Local admin";

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

	//Utilities::echor ($post);
	$Today->post_admin($post);


}


