<?php
namespace DigitalMx\jotr;
use \DigitalMx\jotr\Api;
use \DigitalMx\jotr\CacheSettings as CS;
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

$base = CS::getURL('wgov');
echo "base: $base " . BR;
$api = new Api($base);
$api->apiRequest(CS::getGridpoints('hq') . '/forecast');
