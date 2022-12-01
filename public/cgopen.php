<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];
	$CgOpens = $container['CgOpens'];


//END START


if ($_SERVER['REQUEST_METHOD'] == "POST") {
	//u\echor($_POST, 'post',STOP);
	$CgOpens->save_opens($_POST['open']);
}
$opens = $CgOpens -> get_opens();



	$data['opens'] = $opens;
	u\echor($data);
		echo $Plates->render('cgopen',$data);




