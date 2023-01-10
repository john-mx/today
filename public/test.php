<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);


//BEGIN START
	require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	// use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
 	use DigitalMx\jotr\Utilities as J;
//
//
	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];
	$Cal = $container['Calendar'];

$meta = array(
	'qs' =>  $_SERVER['QUERY_STRING'] ?? '',
	'page' => basename(__FILE__),
	'subtitle' => 'Test page',
	'extra' => "<script src='/js/show_time.js'></script>",

);

echo $Plates->render('head',$meta);
echo $Plates->render('title',$meta);
//END START

$wlocs = ['jr','cw','kv','hq','br'];
$hq = ['hq'];
$twolocs = ['jr','hq'];


J::echor($wlocs);

echo Defs::aq_scale(200);

// what function?

//$f = weather();

// echo "<script>setInterval(dtime(),60);</script>";
// function dtime(){
// echo "time is ";
// echo
// "<script>document.write(show_time())</script>"
// ;}


function refresh($force=false){
	$U = new Utilities();
	echo $U->over_cache_time('wgov');
	exit;
}

function current() {
	global $Today,$Plates,$Defs,$Cal;
	$Today->rebuild_cache_current();
}

function calendar(){
	global $Today,$Plates,$Defs,$Cal;

	$c = $Cal->load_cache();
#u\echor($c);

	$calendar = $Cal->filter_calendar($c,4);
	#u\echor($calendar,'filtered cal',false);
	$platedata = array('calendar'=>$calendar);
	echo $Plates->render('calendar',$platedata);
	exit;
}


function today(){
	global $Today,$Plates,$Defs;

	$z=$Today->build_topics();
	echo $Plates->render('today',['data'=>$z]);
	//u\echor($z,' topics');
	exit;
}

function light(){
	global $Today,$Plates,$Defs;

	$z=$Today->build_topic_light();
	echo $Plates->render('light',['data'=>$z]);
	//echo $Plates->render('conditions',$z);
	echo "<hr>";
	u\echor($z,'data');
	exit;
}

function weather() {
	global $Today,$Plates,$Defs;
	$Today->display_weather(['jr','cw'],2);


}

function weather_gov(){
	global $Today,$Plates,$Defs;
#	$Today->rebuild_cache_wgov();

	$z=$Today->build_topic_weather();
	u\echor($z,'build topic');
	echo $Plates->render('weather-wgov',$z);
	exit;
}


function camps(){
	global $Today,$Plates,$Defs;
	$z=$Today->build_topic_campgrounds();
	u\echor($z,'topic');
	echo $Plates->render('campground',$z);
	exit;
}

function prep () {
	global $Today,$Plates,$Defs;

	$z = $Today->prepare_topics();
	u\echor($z,'topic array', STOP);
}


function alerts() {
	global $Today,$Plates,$Defs;

	echo "Testing " . 'galerts ' . BR;
	$Today->rebuild_cache_galerts();;
	exit;
}

function wapi() {
global $Today,$Plates,$Defs;

	echo "Testing " . 'weather ' . BR;
	$z = $Today->build_topics();
	echo $Plates->render('weather-wapi',$z);
	exit
	;
	}

function wgov(array $loc) {
	global $Today,$Plates,$Defs;

	$z = $Today->rebuild_cache_wgov($loc);
	u\echor($z);
}

function props (){
	global $Today,$Plates,$Defs;

	 "Testing " . 'props ' . BR;
	$z = $Today->rebuild_caches(['properties']);
	exit;
}


function temail ($Plates,$Today) {

//	echo $Today->start_page('test page','b');
	$z = $Today->prepare_today();
	// u\echor ($z,'Today input to plates');

	echo $Plates->render('email2',$z);
}

function tprint (){
global $Today,$Plates,$Defs;
	$Today->buildPDF();
	exit;

}

echo BR . "Done" . BRNL;


exit;

