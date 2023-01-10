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
// 	
// 	$Today = $container['Today'];
// 	$Cal = $container['Calendar'];

$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Index',
	'extra' => "",

);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);
//END START

?>
<h3>Today in the Park</h3>
This site compiles key information about Joshua Tree National Park for visitors.<br>

<ul>
<li><a href='/pages.php' target='pages'>List of Resources</a>
<li><a href='/about.php'>About the Site</a>
<li><a href='/today.php' target='today'>Today in the Park</a>
<li><a href='/rotate.php' targetr='rotate'>Rotating Pages for TV Screen</a>
</ul>
