<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';





	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
	$CM = $container['CacheManager'];

//END START
$meta=array(
	//'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Rebuild Properties',
	'extra' => "",

	);
echo $Plates->render('head',$meta);
echo "<body>";
echo $Plates->render('title',$meta);

?>
<p>This page rebuilds the properties (coordinates, zones, etc.) for the sites used for weather forcasts.  Data is stored in properties.json,  It should not normally change and the stored data is not used for anything except reference.</p>
<?php

if ($z = $CM->rebuild_properties()) {
	echo "Succeeded";
	//Utilities::echor($z,'result');
} else {
	echo "Failed";
}

