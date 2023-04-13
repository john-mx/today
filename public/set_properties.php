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
if (!$container['Login']->checklevel(basename(__FILE__))) exit;
$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Rebuild Properties json',
	]);

echo $Plates->render('head',$meta);
echo $Plates->render('body',$meta);
echo $Plates->render('title',$meta);

?>
<p>This page rebuilds the properties (coordinates, zones, etc.) for the sites used for weather forcasts.  Data is stored in properties.json,  It should not normally change and the stored data is not used for anything except reference.</p>
<?php

if ($z = $CM->rebuild_cache_props()) {
	echo "Succeeded";
	//Utilities::echor($z,'result');
} else {
	echo "Failed";
}

