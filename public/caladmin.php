<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';





	$Plates = $container['Plates'];

	$Cal = $container['Calendar'];
	$Login = $container['Login'];


//END START

$meta=array(
	//'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Calendar admin',
	'extra' => "",

	);
echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);;


if (isset($_POST['pw']) ) {// is login
	$Login->set_pwl($_POST['pw']);

}

$Login->check_pw(5);



if (!empty($_POST) && !isset($_POST['pw'])) {	#Utilities::echor ($_POST,'Post',false);
	$z = $Cal->post_calendar ($_POST['calendar']);



}

$c = $Cal->load_cache();
#Utilities::echor($c);

$calendar = $Cal->filter_calendar($c,0);
#Utilities::echor($calendar,'cal',true);

#add 3 blank recordsw
	for ($i=0;$i<3;++$i) {
		$calendar[] = $Cal::$empty_cal;
	}

$calendar = $Cal->add_types($calendar);



$platedata = array('calendar'=>$calendar);
?>
<form method='post'>

<?php echo $Plates->render('cal-admin',$platedata);?>
<p><input class='submit' type='submit'></p>
</form>
