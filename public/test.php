<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheManager as CM;

ini_set('display_errors', 1);

//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	//
//
//
	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
	$Cal = $container['Calendar'];
	$CM = $container['CacheManager'];
	$PM = $container['PageManager'];



$r = ['root' =>[
	'sub1'=> [
		'l3'=>1,
		'l4'=>2,
		],
	'sub2'=>[
		'l5'=>1,
		'l6'=>2,
	]
	]
	];

U::echor($r);
$j = json_encode($r);
echo $j;

exit;

?>
<hr>
<form method='post'>
<input type='text' name='ttime'>
<input type='submit'>
</form>
