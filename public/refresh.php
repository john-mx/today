<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require_once  './init.php';


	$CM = $container['CacheManager'];


//END START
?>

<html><head>
<title>Refresh All Caches</title>
</head>
<body>
Starting all cache refresh
<?php
echo	$CM->refreshAllCaches ();
echo "Done" . BRNL
. "</body></html>";

exit;

