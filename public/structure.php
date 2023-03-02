<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

//	use DigitalMx\jotr\Utilities as U;
//
// 	use DigitalMx\jotr\Refresh;
//



	$Plates = $container['Plates'];
//

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Structure',

	]);


echo $Plates->render('head',$meta);

echo $Plates->render('title',$meta);
//END START

?>
<img src="/images/structure.jpg" style='height:75vh;' />
