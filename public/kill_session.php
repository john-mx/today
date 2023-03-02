<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Utilities as U;
#ini_set('display_errors', 1);

//BEGIN START
	require_once  './init.php';

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Clear Login ',
	]);


U::echor($_SESSION,'Current Session');
echo "<p>Unsetting</p>";
unset ($_SESSION['loginLevel']);
unset ($_SESSION['loginTime']);

U::echor($_SESSION,'Current Session');


