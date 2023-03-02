<?php
namespace DigitalMx\jotr;
//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Utilities as U;

	$Plates = $container['Plates'];
	$Defs = $container['Defs'];

//END START

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Cache Copy',
	]);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);

//END START
// page to copy caches from the live site into current site
// only runs onb remote site

if (PLATFORM != 'remote') die ("Cannot run this on local machine");

echo "Copies all the caches from the live site to the caches on this site." .BR;
echo "<script>
	if (!confirm('Copying all caches from live site to here.  OK?')){window.location.assign('/pages.php');}
	</script>";

$varlive = '/usr/home/digitalm/Sites/jtnp/live/var';
$varx = REPO_PATH . '/var';
// -p preserves timestamp
$cmd = "cp -p $varlive/* $varx/;ls -ltH $varx;";
//echo $cmd . BR;
 if (!exec($cmd,$output)) {echo "cp failed.";}
 else {foreach ($output as $line) echo $line.BR;}

echo "<p><a href='/pages.php'>Return to Pages</a></p>";