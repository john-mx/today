<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

	$Plates = $container['Plates'];
	
	$Today = $container['Today'];


//END START
$meta=array(
	//'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Rebuild Properties',
	'extra' => "",

	);
echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);

?>
<p>This page rebuilds the properties (coordinates, zones, etc.) for the sites used for weather forcasts.  Data is stored in properties.json,  It should not normally change and the stored data is not used for anything except reference.</p>
<?php

if ($z = $Today->rebuild_properties()) {
	echo "Succeeded";
	//u\echor($z,'result');
} else {
	echo "Failed";
}

