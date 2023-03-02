<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Utilities as U;
#ini_set('display_errors', 1);

//BEGIN START
	require_once  './init.php';

//U::echor($_SESSION,'Current Session');
echo "<p>Unsetting session.</p>";
$qs = $_SERVER['QUERY_STRING'] ?? false;
$container['Login']->logOut($qs);
