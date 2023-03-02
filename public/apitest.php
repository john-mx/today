<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;

use \DigitalMx\jotr\Api;
use \DigitalMx\jotr\CacheSettings as CS;
use DigitalMx\jotr\CacheManager as CM;
use DigitalMx\jotr\Calendar as Cal;


	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

ini_set('display_errors', 1);

//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	$Plates = $container['Plates'];
	$CM = $container['CacheManager'];
	$DM = $container['DisplayManager'];

$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'apitest',
	]);

echo $Plates->render('head',$meta);
echo "<body>";
//echo $Plates->render('title',$meta);
//END START

if (1){
	$CM->rebuild_cache_wgalerts();
//	$DM->build_topic_galerts();


//	U::echor($r);


}


if (0){
$apicode = 'wapi';
echo "Starting $apicode" . BR;
$api = new Api($apicode);
$query= ['key' => CS::getKey('weatherapi')];
$relurl = '';
foreach (['jr','cw','br'] as $loc ){
//$loc = 'jr';

	$loginfo = "$apicode-$loc";
	$query['q'] = LS::getCoords($loc);

	$resp[$apicode][$loc]=$api->apiRequest($loginfo, $relurl,$query);
}

}

if (0) {
$apicode = 'wgov';
echo "Starting $apicode" . BR;
$api = new Api($apicode);
foreach (['jr','cw','br'] as $loc ){
	$relurl = LS::getGridpoints($loc) . '/forecast';
	$loginfo = "$apicode-$loc";
	$resp[$apicode][$loc]=$api->apiRequest($loginfo, $relurl);
}

}

if (0) {  //current works, forecast returns nothing
$apicode = 'airnow';
echo "Starting $apicode" . BR;
$api = new Api($apicode);
$relurl = '';
foreach (['jr','br','cw'] as $loc ){
	$loginfo = "$apicode-$loc";
	[$lat,$lon] = CM::split_coord($loc);
	$query = [
		'format'=>'application/json',
		'latitude' => $lat,
		'longitude' => $lon,
		'distance' => 75,
		'API_KEY' => CS::getKey($apicode),
	];
	U::echor($query,$loginfo);

	$resp[$apicode][$loc]=$api->apiRequest($loginfo, $relurl,$query);
}
}

if (0) {//a
$apicode = 'airowm3';
//https://api.openweathermap.org/data/3.0/onecall?lat={lat}&lon={lon}&exclude={part}&appid={API key}
echo "Starting $apicode" . BR;
$api = new Api($apicode);
$relurl = '';
foreach (['jr','br','hq','cw'] as $loc ){
	$loginfo = "$apicode-$loc";
	[$lat,$lon] = CM::split_coord($loc);
	$query = [
		'exclude'=>'minutely,alerts',
		'lat' => $lat,
		'lon' => $lon,
		'appid' => CS::getKey($apicode),
	];
	U::echor($query,$loginfo);

	$resp[$apicode][$loc]=$api->apiRequest($loginfo, $relurl,$query);
}

$CM->writeCache('airowm3',$resp);
}





//U::echor($resp);
