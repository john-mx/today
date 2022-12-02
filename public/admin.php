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


//END START
// check for login status

if (isset($_POST['pw']) ) {// is login
	$Login->set_pwl($_POST['pw']);
	show_admin($Today,$Plates);
	exit;
}
$Login->check_pw(1);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		post_data ($_POST,$Today);
		exit;
} else {
	$meta['title'] = 'Admin JOTR Today';
	echo $Plates->render ('start',['meta'=>$meta]);

	show_admin($Today,$Plates);
	exit;
}



####################

function show_admin ($Today,$Plates) {
 //echo $Today->start_page('Admin JOTR Today','h'); #add hidden js


	$y = $Today-> prepare_admin();

// 	u\echor($y, 'To Plates', );

	echo $Plates->render('admin',$y);


}
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

	echo "<p><b>Success</b></p>
		<p>Go to <a href='/'>Index page</a></p>";
	echo "
		<p>Go to <a href='/admin.php' >/admin.php</> to reload this page. </p>
		";

}


