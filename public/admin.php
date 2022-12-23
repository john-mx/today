<?php
namespace DigitalMx\jotr;

/*
	today admin page

	first checks for login level.
	If fails, then shows login screen.  Logging in returns to this screen.

*/


#ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];
	$Login = $container['Login'];
	$Cal = $container['Calendar'];


//END START

$meta=array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Site Admin',
	'extra' => "",

	);

if (isset($_POST['pw']) ) {// is login
	$Login->set_pwl($_POST['pw']);
}


$Login->check_pw(1);

//u\echor($_POST,'post');

if (!empty($_POST) && !isset($_POST['pw'])) {
		post_data ($_POST,$Today);
		echo "<script>window.location.href='/admin.php';</script>";
		exit;

} else {

// get calendar


	echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);

		$y = $Today-> prepare_admin();
// u\echor($y);
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

	//u\echor ($post);
	$Today->post_admin($post);


}


