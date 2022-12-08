<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;


	$Plates = $container['Plates'];
	$Defs = $container['Defs'];
	$Today = $container['Today'];
	$Cal = $container['Calendar'];

	echo $Plates->render('start',['title'=>'Test Page']);

//END START

$wlocs = ['jr','cw','kv','hq','br'];
$hq = ['hq'];
$twolocs = ['jr','hq'];


// what function?

$f = tprint();

function refresh($force=false){
		global $Today,$Plates,$Defs;
	$z=$Today->refresh_caches($force);
	exit;
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

	$z=$Today->prepare_topics();
	echo $Plates->render('today',['data'=>$z]);
	u\echor($z,'prepared topics');
	exit;
}

function light(){
	global $Today,$Plates,$Defs;

	$z=$Today->build_topic_light ();
	echo $Plates->render('light',['data'=>$z]);
	u\echor($z,'prepared liught');
	exit;
}



function weather_b(){
	global $Today,$Plates,$Defs;
#	$Today->rebuild_cache_wgov();

	$z=$Today->build_topic_weather();
	echo $Plates->render('weather_brief',$z);
	exit;
}


function camps(){
	global $Today,$Plates,$Defs;
	$z=$Today->build_topic_campgrounds();

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

function weather() {
global $Today,$Plates,$Defs;

	echo "Testing " . 'weather ' . BR;
	$z = $Today->prepare_topics();
	echo $Plates->render('weather',$z);
	exit
	;
	}
function props (){
	global $Today,$Plates,$Defs;

	 "Testing " . 'props ' . BR;
	$z = $Today->rebuild_caches(['properties']);
	exit;
}

function t1 () {
	global $Today,$Plates,$Defs;

$z = $Today->load_cache('wapi',true);

u\echor ($z, 'result of test');
}

function t2 () {
	global $Today,$Plates,$Defs;

	echo $Today->start_page('test page','b');

	 u\echor ($z,'Today input to plates');

	echo $Plates->render('today-boot',$z);
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
function t4 ($Plates,$Today) {

	$data = file_get_contents(REPO_PATH . '/public/pages/today2.html');
	//echo $data; exit;
	$Today->print_pdf($data,'pages/test2.pdf');
}
echo "Done" . BRNL;


exit;

