<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheManager as CM;

ini_set('display_errors', 1);


//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	//
//
//
	$Plates = $container['Plates'];

	$DM = $container['DisplayManager'];
	$Cal = $container['Calendar'];
	$CM = $container['CacheManager'];

$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Test page',
	'extra' => "<script src='/js/show_time.js'></script>",

);

echo $Plates->render('head',$meta);
echo "<body>";
echo $Plates->render('title',$meta);
//END START

$wlocs = ['jr','cw','kv','hq','br'];
$hq = ['hq'];
$twolocs = ['jr','hq'];


//U::echor($wlocs);

//echo Defs::aq_scale(200);

//U//::showHelp('notices');

// what function?

$f = tours();

// echo "<script>setInterval(dtime(),60);</script>";
// function dtime(){
// echo "time is ";
// echo
// "<script>document.write(show_time())</script>"
// ;}

function tours() {
	global $CM;
	$CM->rebuild_cache_tours();
	exit;
}

function reccamps(){
	global $CM;
	$CM->updateCampsRec();
	exit;
}
function refresh($force=false){
	$U = new Utilities();
	echo $U->over_cache_time('wgov');
	exit;
}

function current() {
	global $CM,$Plates,$Cal;
	$CM->rebuild_cache_current();
}

function calendar(){
	global $DM,$Plates,$Cal;

	$c = $Cal->load_cache();
#Utilities::echor($c);

	$calendar = $Cal->filter_calendar($c,4);
	#Utilities::echor($calendar,'filtered cal',false);
	$platedata = array('calendar'=>$calendar);
	echo $Plates->render('calendar',$platedata);
	exit;
}


function today(){
	global $DM,$Plates;

	$z=$DM->build_topics();
	echo $Plates->render('today',['data'=>$z]);
	//Utilities::echor($z,' topics');
	exit;
}

function light(){
	global $DM,$Plates;

	$z=$DM->build_topic_light();
	echo $Plates->render('light',['data'=>$z]);
	//echo $Plates->render('conditions',$z);
	echo "<hr>";
	Utilities::echor($z,'data');
	exit;
}

function weather() {
	global $DM,$Plates;
	$DM->display_weather(['jr','cw'],2);


}

function weather_gov(){
	global $DM,$Plates;
#	$Today->rebuild_cache_wgov();

	$z=$Today->build_topic_weather();
	Utilities::echor($z,'build topic');
	echo $Plates->render('weather-wgov',$z);
	exit;
}


function camps(){
	global $DM,$Plates;
	$z=$DM->build_topic_campgrounds();
	Utilities::echor($z,'topic');
	echo $Plates->render('campground',$z);
	exit;
}

function prep () {
	global $DM,$Plates;

	$z = $DM->prepare_topics();
	Utilities::echor($z,'topic array', STOP);
}


function alerts() {
	global $DM,$Plates;

	echo "Testing " . 'galerts ' . BR;
	$DM->rebuild_cache_galerts();;
	exit;
}

function wapi() {
global $DM,$Plates;

	echo "Testing " . 'weather ' . BR;
	$z = $DM->build_topics();
	echo $Plates->render('weather-wapi',$z);
	exit
	;
	}

function wgov(array $loc) {
	global $DM,$Plates;

	$z = $DM->rebuild_cache_wgov($loc);
	Utilities::echor($z);
}

function props (){
	global $DM,$Plates;

	 "Testing " . 'props ' . BR;
	$z = $DM->rebuild_caches(['properties']);
	exit;
}


function temail ($Plates,$Today) {

//	echo $Today->start_page('test page','b');
	$z = $Today->prepare_today();
	// Utilities::echor ($z,'Today input to plates');

	echo $Plates->render('email2',$z);
}

function tprint (){
global $DM,$Plates;
	$DM->buildPDF();
	exit;

}

echo BR . "Done" . BRNL;


exit;

