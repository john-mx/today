<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];


//END START

$wlocs = ['jr','cw','kv','hq','br'];

// if ($Today->set_properties ($wlocs) ){
// 	echo "Succeeded";
// } else {
// 	echo "Failed";
//}


if ($z = $Today->rebuild_properties() {
	echo "Succeeded";
	u\echor($z,'result');
} else {
	echo "Failed";
}

