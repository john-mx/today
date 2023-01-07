<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);


//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

//	use DigitalMx\jotr\Utilities as U;
// 	use DigitalMx as u;
// 	use DigitalMx\jotr\Refresh;
// 	use DigitalMx\jotr\Definitions as Defs;


	$Plates = $container['Plates'];
// 	$Defs = $container['Defs'];
// 	$Today = $container['Today'];
// 	$Cal = $container['Calendar'];

$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Today in the Park',
	'extra' => "",

);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);
//END START

?>
<h3>Today in the Park</h3>
This site compiles key information about Joshua Tree National Park for visitors.<br>
For more information:
<ul>
<li><a href='/about.php'>About the Site</a>
<li><a href='/today.php'>Today in the park</a>
