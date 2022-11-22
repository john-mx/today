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
	$Log = $container['Login'];



//END START

echo <<<EOT
<!DOCTYPE html>
<head>
<link rel='stylesheet' href='/css/main.css'>
<title>JOTR Today Calendar</title>

</head>
<body>
EOT;

// check for login status


$c = $Cal->load_cache();
#u\echor($c);

$calendar = $Cal->filter_calendar($c,4);
#u\echor($calendar,'filtered cal',false);


$platedata = array('calendar'=>$calendar);
echo $Plates->render('calendar',$platedata);
exit;


