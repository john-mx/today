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
	$Cal = $container['Calendar'];
	$Login = $container['Login'];


//END START

meta=array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Calendaar admin',
	'extra' => "",

	);
echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);;


if (isset($_POST['pw']) ) {// is login
	$Login->set_pwl($_POST['pw']);
}

$Login->check_pw(1);



if (!empty($_POST) && !isset($_POST['pw'])) {	#u\echor ($_POST,'Post',false);
	$z = $Cal->post_calendar ($_POST['calendar']);



}

$c = $Cal->load_cache();
#u\echor($c);

$calendar = $Cal->filter_calendar($c,0);
#u\echor($calendar,'cal',true);

#add 3 blank recordsw
	for ($i=0;$i<3;++$i) {
		$calendar[] = $Cal::$empty_cal;
	}

$calendar = $Cal->add_types($calendar);



$platedata = array('calendar'=>$calendar);
echo $Plates->render('caladmin',$platedata);
exit;


