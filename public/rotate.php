<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START


	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';



	use DigitalMx\jotr\Calendar;

	$Plates = $container['Plates'];
	$CM = $container['CacheManager'];
	$DM = $container['DisplayManager'];


//Utilities::echor ($_SESSION);
//END START
$topics = $DM->build_topics();

$admin=$CM->loadCache('admin');
//U::echor($admin,'admin cache', STOP);
$local = $_SESSION['local'] ?? [];

$qs = $_SERVER['QUERY_STRING'];

if ($qs == '' || $qs== 'snap'){ #rotate
	$rotate = (!empty($local['rotate']??''))?$local['rotate'] : $admin['rotate'] ;
	$rdelay = (isset($local['rdelay']) && $local['rdelay'] > 0)?$local['rdelay']: $admin['rdelay'];

	$rotation =  [
	'pageIds' => $rotate,
	'rdelay' => $rdelay,
	'advice' => explode("\n",$admin['advice']),
	'animation' => 'snap',

		];

} elseif ($qs == 'scroll') {
	$rotation = [
	'advice' => explode("\n",$admin['advice']),
	'animation' => 'scroll',
	];
} elseif ($qs == 'nosnap'){
	$rotation = [
		'animation' => '',
	];
} else {
	echo "Oops.  no qs match: $qs" ; exit;
}
$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => TODAY,
	'sunset' => $DM->sunset,
	'rotation' => $rotation,
	'fixedAdvice' => $admin['fixedAdvice'],
	]);
//U::echor($meta,'meta',STOP);
	echo $Plates->render ('head',$meta);

	echo $Plates->render('title',$meta);

	echo $Plates -> render('rotate',$meta) ;

?>
