<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require  './init.php';
	
	use DigitalMx\jotr\Today;

	$Today = $container['Today'];


//END START

echo	$Today->refresh_caches ();
echo "Done";

exit;

