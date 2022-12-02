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

echo $Plates->render('start',['meta'=>['title'=>'Rebuild Properties']]);

?>
<p>This page rebuilds the properties (coordinates, zones, etc.) for the sites used for weather forcasts.  Data is stored in properties.json,  It should not normally change and the stored data is not used for anything except reference.</p>
<?php

if ($z = $Today->rebuild_properties()) {
	echo "Succeeded";
	//u\echor($z,'result');
} else {
	echo "Failed";
}

