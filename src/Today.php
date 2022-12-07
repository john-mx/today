<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	//require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Log;


//END START



// parse_str($_SERVER["QUERY_STRING"], $params);
// //u\echor ($params);
// echo "Syntax: r2.php?Force&Index&Data;\nForce to force updates; Index to xfer result to index.php; Data to show data arrays" . BRNL;
//
// u\echor ($params,'Parameters:') . BRNL;
// $Force = (isset($params['Force']))? 1:0 ; #1 = force updates now. 0 = use regular timing.
// if ($Force) echo "Forcing updates" .BRNL;
// $only = $params['only'] ?? ''; // only=section






// u\echor (CACHE); exit;

/* SECTIONS
data is divided into sections. Each section has its own update
process and cached data file.

info	Contains latest update time, current date being displayed

weather	contains forecasts for today and next 2 days (3 days) for each designated location: geneally jumbo, 29, cottonwood and black rock.

air	air quality info for jumbo rocks

fire	fire danger and messages (derived from local and defs)

camps	availability and status for each campground.

local	manual weather notice, fire notice, current fire danger, pithy statement, announcements.  General campground announcement


light	sunrise, moon phase, etc

calendar data from park calendar assembled into a json file

*/

// model for building and reading the local array



class Today {

public static $dummy_today = array
(
		'fire_level' => 'Low',


				'cgavail' =>  array(
					'ic' => 'Reservations',
					'jr' => 'Reservations',
					'sp' => 'Reservations',
					'hv' => 'Open',
					'be' => 'Closed',
					'wt' => 'Closed',
					'ry' => 'Reservations',
					'br' => 'Reservations',
					'cw' => 'Reservations',
					),

				'cgstatus' => array (
					'ic' => 'Partially Open',
					'jr' => '',
					'sp' => '',
					'hv' => '',
					'be' => 'May open if Hidden Valley Fills Up',
					'wt' => '',
					'ry' => '',
					'br' => '',
					'cw' => '',
					),


			'target' => 'July 3, 2022',
			'updated' => '2 Jul 21:55',
			'fire_level' => 'High',
			'pithy' => 'Something Pithy',
			'announcements' => '40 Palms Canyon is closed for the summer.',
			'weather_warn' => 'Heat Warning!',

);



private static $snap_script = <<<EOT
<script>

function pageScroll() {
    	window.scrollBy(0,3); // horizontal and vertical scroll increments
    	scrolldelay = setTimeout('pageScroll()',50); // scrolls every 100 milliseconds
            if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
        		scrolldelay = setTimeout('PageUp()',2000);
    		}

}

function PageUp() {
	window.scrollTo(0, 0);
}

</script>


<script>
	let timeout = setTimeout(() => {
  document.querySelector('#target').scrollIntoView();
}, 5000);

(function() {
  document.querySelector('#bottom').scrollIntoView();
})();
</script>


EOT;



###############################

public function __construct($c){
	$this->Plates = $c['Plates'];
	$this -> Defs = $c['Defs'];

	$this->Cal = $c['Calendar'];
	$this->Cg = $c['CgOpens'];
	// locations to use for weather report
	$this -> wlocs = ['jr','cw','br'] ; // weather locations
	$this -> airlocs = ['jr','cw','br']; // air quality locations

	$this -> max_age = Defs::$cache_times;
	//$this -> properties = $this->load_cache('properties');
	$this->cache_lock = REPO_PATH . "/data/cache.lock";



}

public function rebuild_pages ()  {
	// rebuilds caches and regenerates today pages

	$y = $this->prepare_today ($force);
	// set forecee to true to force all cahces to rebuild now, instead of on schedule



	$page_body = $this->Plates -> render('main',$y);

	$wapi_page = $this->start_page('Today in the Park (wapi)')
		. $page_body;
	file_put_contents (SITE_PATH . '/pages/wapi.html',$wapi_page);

	$scroll_page = $this->start_page('Today in the Park (scrolling)','s')
		. $page_body . self::$scroll_script;
	file_put_contents( SITE_PATH . '/pages/scroll.html', $scroll_page);

	$snap_page = $this->start_page('Today in the Park (snap)','z')
		. $page_body  . self::$snap_script;
	file_put_contents( SITE_PATH . '/pages/snap.html', $snap_page);


// 	$page_body_wgov = $this->Plates -> render ('today2',$y);
// 	$new_page = $this->start_page('Today in the Park (weather.gov)')
// 		. $page_body_wgov;
// 	file_put_contents (SITE_PATH . '/pages/wgov.html',$new_page);

	// $page_body_con = $this->Plates -> render ('today3',$y);
// 	$new_page = $this->start_page('Today in the Park (condensed)')
// 		. $page_body_con;
// 	file_put_contents (SITE_PATH . '/pages/today3.html',$new_page);

	$page_body_txt = $this->Plates -> render ('ptext',$y);
	$new_page = $this->start_page('Today in the Park (text only)')
		. $page_body_txt ;
	file_put_contents (SITE_PATH . '/pages/ptext.html',$new_page);

	$page_body_em = $this->Plates -> render ('email2',$y);
	$new_page = "<html><head><title>Today in the Park (for email)
		</title></head><body style='width:700px'>";
		$new_page .= $page_body_em ;
	file_put_contents (SITE_PATH . '/pages/email.html',$new_page);

$page_body_print = $this->Plates -> render ('print',$y);
	$print_page = $this->start_page('Today in the Park (print)','p')
		. $page_body_print ;
	file_put_contents( SITE_PATH . '/pages/print.html', $print_page);
	$this_day = date('Y-d-m');
	// make a pdf version if none exists.  This limits to 1 per day.
	$pdf = '/pages/' . "${this_day}.pdf";
	if (!file_exists(SITE_PATH . $pdf)){
		$this->print_pdf($print_page,$pdf);
	}


	Log::info( "Pages updated" );
}
public function prepare_topics(){
	/*
		build topic arrays from caches and defs.
		Each topic goes to a display template.
		Overall display is built by combining display templates.
		Topics are:
			general: alerts, announcements, pithy, fire
			weather: site->data
			campgrounds: cg->status, notes, opens, etc
			sun: sunrise, sunset, uv, air
			calendar: events
		*/

		$topics = [];
	#	foreach (['general','weather','campgrounds','sun','calendar'] as $topic) {

		$topics = array_merge(
			$this->build_topic_general(),
			$this->build_topic_weather(),
			$this->build_topic_campgrounds(),
			$this->build_topic_light(),
			$this->build_topic_air(),
			$this->build_topic_calendar(),



		);


//	u\echor($topics,'topics',NOSTOP);

		return $topics;
}

public function build_topic_calendar() {
	if (!$z=$this->load_cache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }

#	u\echor($z,'calendar',STOP);
	$y=$this->Cal->filter_calendar($z,2);
	return ['calendar' => $y];
}
public function build_topic_air() {
	$z=[];
	if(!$z=$this->load_cache('airnow')){
	 	Log::error ("Could not load cache air");
	 	return [];
	 }
	$y = $this->format_airnow($z);
	return ['air' => $y];

}


public function build_topic_light() {
	$z = [];
	#$light['x'] = 'x';
	if (!$y = $this->load_cache('wapi') ){
	 	Log::error ("Could not load cache wapi");
	 	return [];
	 }
	$zz = $this->format_wapi($y);

	#u\echor($zz,'formatted light', NOSTOP);

	$z['light']= $zz['light'];
	$z['light']['moonpic'] = $this->Defs->getMoonPic($z['light']['moonphase']);

	$z['uv'] = $this->uv_data($z['light']['uv']);


#	$z['air'] = $this->format_airowm($y['airowm']) ?? [];

	return ['light' => $z];
}


public function build_topic_weather() {
	if (!$w = $this->load_cache('wgov') ){
	 	Log::error ("Could not load cache wgov");
	 	return [];
	 }
	$z = $this->format_wgov($w);
#u\echor($z,'formatted wgov');

	//get current temp
	$y = $this->load_cache('wapi');
	$z['current'] = $y['jr']['current'];
	//u\echor($z['current'],'z',STOP);
	return $z;
}

public function build_topic_campgrounds() {
	if (!$y = $this->load_cache('admin') ){
	 	Log::error ("Could not load cache admin");
	 	return [];
	 }

	$w['camps']['cg_notes'] = $y['cgnotes'];
	$w['camps']['cg_status'] = $y['cgstatus'];
	if (!$w['camps']['cg_open'] = $this->load_cache('cgopen')){
	 	Log::error ("Could not load cache cg_open");
	 	return [];
	 }
	$w['camps']['asof'] = $this->getMtime('cgopen');


	$w['camps']['cgfull'] = !array_filter($w['camps']['cg_open']);

#	u\echor($w);

	return $w;
}

public function build_topic_general() {
	/* load date from admin cache, then reformat for display */

	if (!$y = $this->load_cache('admin') ){
	 	Log::error ("Could not load cache admin");
	 	return [];
	 }

		//clean text for display (spec chars, nl2br) but don't change stored info.

		 	$t = $this->clean_text($y['pithy']);
			 $z['pithy'] = $t;

			$t = $this->format_alerts ($y['alerts']);
			$z['notices']['alerts'] = trim($t);

				$t = $this->clean_text($y['announcements']);
			$z['notices']['announcements'] = trim($t);

			$fire_level = $y['fire_level'];
			$z['fire']['level'] = $fire_level;
			$z['fire']['color'] = Defs::get_firecolor($fire_level);

			$z['version'] = file_get_contents(REPO_PATH . "/data/version") ;
			$z['target'] = date('l M j, Y');

			$z['advice'] = $this->clean_text($y['advice']);

	//u\echor($z,'topic general', NOSTOP);
	return $z;

}

public function Xprepare_today($force=false) {
 /*set force true or false to force cache updates
  get sections needed and assemble inito data array y
  which is ready for the template to use.

  all the raw data is read into the y array from caches.
  Then the information is transferred into the z array, which is what will actually be used to generate the pages.
  The transformation involves choosing which parameters will be used
  and compiling some computed things like colors for levels and
  text for levels.



  */


	foreach (['wgov','airowm','airnow','calendar','admin','wgova','camp','cgopen'] as $section) {
		if (!$y[$section] = $this -> load_cache ($section, $force)){
	 	Log::error("Could not load cache $section");
	 	return [];
	 }
	}
 	u\echor ($y, 'y array into today',STOP);

//	echo 'Version: ' . $v . BRNL; exit;
$z=[];
	$z['version'] = file_get_contents(REPO_PATH . "/data/version") ;
	$z['target'] = date('l M j, Y');

	$z['admin'] = $y['admin'];
		//clean text for display (spec chars, nl2br) but don't change stored info.
	foreach(['pithy','fire_warn','weather_warn','announcements','alerts'] as $txt){
		if (!empty ($y['admin'][$txt])) {
			$z['admin'][$txt] = $this->clean_text($y['admin'][$txt]);
		}
	}

	$z['wapi']['fc'] = $this->format_wapi($y['wapi']);
	$z['wgov']['fc'] = $this->format_wgov($y['wgov']);

	$z['light']= $z['wapi']['fc']['light'];
	$z['light']['moonpic'] = $this->Defs->getMoonPic($z['light']['moonphase']);

	$z['uv'] = $this->uv_data($z['wapi']['fc']['forecast']['jr'][0]['uv']);

	$z['fire'] = $this->fire_data($y['admin']['fire_level']);

	$z['air'] = $this->format_airowm($y['airowm']);

	$z['camps']['info'] = $y['camps'];
	$z['camps']['cgopen'] = $y['cgopen'];
	$z['camps']['asof'] = $this->getMtime('cgopen');

	$z['calendar'] = $this->Cal->filter_calendar($y['calendar'],4);


	Log::info('formed today array');
u\echor($z, 'z array for today',STOP);
	return $z;
}


public function prepare_admin() {
// get sections needed for the admin form
	if (!$y['admin'] = $this->load_cache('admin')){
	 	Log::error ("Could not load cache admin");
	 	return [];
	 }
// 	u\echor ($y, 'read admin cache', NOSTOP);

	// set firelevel options array
	$fire_levels = array_keys(Defs::$firewarn);
	$y['admin']['fire_level_options'] = u\buildOptions($fire_levels,$y['admin']['fire_level']);

// camps
	foreach (array_keys(Defs::$campsites) as $cgcode){
		$opt = u\buildOptions(Defs::$cgstatus, $y['admin']['cgstatus'][$cgcode]);
		$opts[$cgcode]  = $opt;
		$notes[$cgcode] = $y['admin']['cgnotes'][$cgcode];

	}
	$y['admin']['cg_options'] = $opts;
	$y['admin']['cg_notes'] = $notes;

	$y['admin']['cgopen'] = $this->load_cache('cgopen') ?? [];
	$y['admin']['cgfull'] =  (!array_filter($y['admin']['cgopen'])) ? 1:0;


	//$y['calendar'] = $this->filter_calendar($this->load_cache('calendar'));
	// if (! $galerts = $this->rebuild_cache_galerts() ){
// 	}

	if (!$y['galerts'] = $this->load_cache('galerts') ){
	 	Log::error ("Could not load cache galerts");
	 	return [];
	 }


// u\echor ($y, 'Y to admin',NOSTOP);
	return $y;
}


public function format_galert($galerts){


#	u\echor($cache);
	$x = [];
	foreach ($galerts['features'] as $galert){
#	u\echor($galert,'galert',STOP);

		$props = $galert['properties'];

			$alert_exp = strtotime($props['expires']);
			if ($alert_exp < time()) continue;
			$alert=[];

			$alert['headline'] = $props['headline'];
			$alert['category'] = $props['category'];
			$alert['event'] = $props['event'];
			$alert['expires'] = $props['expires'];
			$alert['description'] = $props['description'];
			$alert['instruction'] = $props['instruction'];
			$alert['expire_ts'] = $alert_exp;


			$x[] =$alert;

		}
	#	if (empty($x)){ $x = ['No Alerts'];}
		return $x;

}

public function post_admin ($post) {
 /* insert posted data and dependencies into cacjes


*/
// u\echor ($post, 'Posted');

//  admin cache
	$y=[];
	$y['announcements'] = trim($post['announcements']);
	$y['updated'] = date('d M H:i');
	$y['pithy'] = trim(u\despecial($post['pithy']));
//fire

	$y['fire_level'] = $post['fire_level'];
//weather
	$y['alerts'] = trim($post['alerts']);

	$y['advice'] = trim($post['advice']);


	$y['cgstatus'] = $post['cgstatus']; // array
// 	u\echor ($y,'to write admin cache',STOP);
	$y['cgnotes']  =$post['cgnotes'] ; //array

	$this -> write_cache('admin',$y);

	$cgo = $post['cgopen'];
	// set opens based on status
	foreach ($y['cgstatus'] as $cg=>$status){
		if ($status == 'Closed'){$cgo[$cg] = 0;}
	}
	$this->write_cache('cgopen',$cgo);

//rebuild the pages
	#$this->rebuild();




}

#-----------   SUBS ------------------------------



public function load_cache ($section, bool $force=false) {

		$refresh = $force;
#echo "loading cache $section" . BR;
		if (! $refresh && !file_exists (CACHE[$section])) {
			$refresh = true;
			Log::notice ("Cache $section does not exist. Refreshing.");
		} elseif (!$refresh) {
			$mtime = $this->getMtime($section);
			$maxtime = $this->Defs->getMaxtime ($section) ;
			// $maxtime set to 0 if cache is maintanined elswhere,
			// by admin or by resetting another cache.
			$diff = time() - $mtime;
			if ($maxtime && ( $diff > $maxtime )){
					$refresh = true;
// 					echo "Timeout on $section cache" . BRNL;
			}
		}


		if ($refresh) {
			if (0 && ! $this->refresh_cache($section) ) {
				u\echoAlert ("Unable to refresh cache: $section.  Using old version.");
				Log::notice("Unable to refresh cache $section");
			}

		}
		$lock_count = 0;
		while (file_exists($this->cache_lock)){
			++$lock_count;
			if ($lock_count > 3){
				Log::error("Cannot read cache $section due to lock");
				return [];
			}
			sleep (2);
		}

		$y = json_decode (file_get_contents(CACHE[$section]), true);

#u\echor($y,$section) . BR;


		return $y;
}




########  CACHES #############
public function refresh_caches($force=false) {
$v=true; #verbose
// refreshes all the external caches, if they are due
	#$caches = ['wapi','airq','airowm','wgov','airnow','galerts'];
		if ($this->over_cache_time('wapi') || $force) {
				$this->rebuild_cache_wapi();

		}
		if ($this->over_cache_time('airq') || $force) {
			$this->rebuild_cache_airq();

		}
		if ($this->over_cache_time('airowm') || $force) {
			$this->rebuild_cache_airowm();
		}
		if ($this->over_cache_time('wgov') || $force) {
				$this->rebuild_cache_wgov();
		}
		if ($this->over_cache_time('airnow') || $force) {
				$this->rebuild_cache_airnow();
		}
		if ($this->over_cache_time('galerts') || $force) {
				$this->rebuild_cache_galerts();
		}

			#	$this -> rebuild_properties('jr');

	}


private function rebuild_cache_wapi($locs=[] ) {
	$x=[];
	$src = 'wapi';
	if (empty($locs)){$locs = $this->wlocs;}

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = 'http://api.weatherapi.com/v1/forecast.json?key=' . $this->Defs->getKey('weatherapi') . '&q='. $this->Defs->getCoords($loc) . '&days=3&aqi=yes&alerts=yes';
		$expected = '';
		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_curl($loginfo,$url, $expected, $curl_header) ) {
			$aresp = [];
		}

		$x[$loc] = $aresp;
	} # next loc

	$this->write_cache($src,$x);
	Log::info('Rebuilt cache wapi.');
	return $x;
}

private function rebuild_cache_airq() {
	$x=[];
	$src = 'airq';
	$locs = $this->airlocs;
	$curl_header = [
			"X-RapidAPI-Host: air-quality.p.rapidapi.com",
			"X-RapidAPI-Key: 3265344ed7msha201cc19c90311ap10b167jsn4cb2a9e0710e"
				];
	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$expected = 'aqi'; #field to test for good result
		$url = "https://air-quality.p.rapidapi.com/current/airquality?lon=$lon&lat=$lat";

		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_curl($loginfo,$url, $expected, $curl_header) ) return false;

		$x[$loc] = $aresp;
	} # next loc

	$this->write_cache($src,$x);
	Log::info('Rebuilt cache airq.');

}

private function rebuild_cache_airowm() {
	$x=[];
	$src = 'airowm';
	$locs = $this->airlocs;

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = "http://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid=" . $this->Defs->getKey('openweathermap');

		$expected = '';

		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_curl($loginfo,$url, $expected, $curl_header) ) return false;
		$x[$loc] = $aresp;
	} # next loc

	$this->write_cache($src,$x);
	Log::info('Rebuilt cache airowm.');

}

public function rebuild_cache_wgov() {
/*
https://api.weather.gov/gridpoints/{office}/{grid X},{grid Y}/forecast

https://api.weather.gov/gridpoints/PSR/13,102/forecast

zones:
Lost Horse (west JtNP) zoneid=CAZ560
JTNP East CAZ561
JTNP CAZ230
Morongo basin CAZ525
Coachella valley CAZ061
PSP CAZ261
Yucca CAZ228
Salton CAZ563

modoc county CAZ285
		geocode SAME 006049



29p airport SITE KNXP

metadata by lat-lon
https://api.weather.gov/points/{lat},{lon}

get stations for zone
https://api.weather.gov/gridpoints/PSR/13,102/stations

FIPS
CA = 06
San Bern = 071
Riverside = 065

Site
Blackrock 9002
29p 0017
cw 0010
coord: 34.0714,-116.3906,

*/

	$x=[];
	$src = 'wgov';
	$locs = $this->wlocs;
	#$locs = ['br'];

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = "https://api.weather.gov/gridpoints/" . $this->Defs->getGridpoints($loc) ."/forecast";
		#$url = "https://api.weather.gov/points/$lat,$lon";
		$expected = 'properties';

		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_curl($loginfo,$url, $expected, $curl_header) ) return false;

		$x[$loc] = $aresp;
	} # next loc
Log::info('Rebuilt cache wgov.');
	$this->write_cache($src,$x);

}

private function rebuild_cache_airnow() {
/*
airnow (from eps.gov)
api key Your API Key: 7FB4BEFF-A568-4FE4-8E67-F1EE36B5C04B
format appliction/json

https://www.airnowapi.org/aq/forecast/latLong/?format=application/json&latitude=39.0509&longitude=-121.4453&date=2022-07-16&distance=25&API_KEY=7FB4BEFF-A568-4FE4-8E67-F1EE36B5C04B
(no results for foreccast

current:
https://www.airnowapi.org/aq/observation/latLong/current/?format=application/json&latitude=33.99&longitude=-116.14&distance=25&API_KEY=7FB4BEFF-A568-4FE4-8E67-F1EE36B5C04B
(gives black rock)


*/
	$x=[];
	$src = 'airnow';
	$locs = $this->airlocs;

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = "https://www.airnowapi.org/aq/observation/latLong/current/?format=application/json&latitude=$lat&longitude=$lon&distance=25&API_KEY=" . $this->Defs->getKey('airnow');
				$expected = 'AQI'; #field to test for good result
		$loginfo = "$src:$loc";
		$this->lock_cache();
		if (!$aresp = $this->get_curl($loginfo,$url, $expected, $curl_header) ) return false;
		$x[$loc] = $aresp;
	} # next loc

	$this->write_cache($src,$x);
	Log::info('Rebuilt cache airnow.');

}

private function lock_cache(){
		touch ($this->cache_lock) ;
}
private function unlock_cache($section = ''){
	// Log::info ("keeping cahce lock $section");
// 	return [];
		if (file_exists($this->cache_lock) ){
				unlink ($this->cache_lock);
		} else {
			Log::error("cache_lock does not exist: $section.");
		}
}
public function rebuild_cache_galerts () {

	/* must get uniform format for alerts to display right
	headline
	category
	event
	expires
	description
	action


*/
	$y=[];

	$src = 'wapi';

	 if (!$r = $this->load_cache('wapi') ) {
	 	Log::error ("Could not load cache wapi");
	 	return [];
	 }
 //	u\echor($r,'From wapi',STOP);
		$alerts = $r['jr']['alerts']['alert'];
		$x=[];
		foreach ($alerts as $alertno => $ad){
			$alert_exp = strtotime($ad['expires']);
			if ($alert_exp < time()) continue;
			$alert=[];
			$alert['headline'] = $ad['headline'];
			$alert['category'] = $ad['category'];
			$alert['event'] = $ad['event'];
			$alert['expires'] = $ad['expires'];
			$alert['description'] = $ad['desc'];
			$alert['instruction'] = $ad['instruction'];
			$alert['expire_ts'] = $alert_exp;

			$x[] =$alert;
		}
		Log::info('Updated wapi for alerts');
		$y[$src] = $x;


	$src = 'wgovalerts';
		if ( ! $r = $this->rebuild_cache_wgalerts() ) {
	 	Log::error ("Could not load cache wapi");
	 	return [];
	 }
//	u\echor($r,'get wgalerts',NOSTOP);
		$items = $r['features'];

		foreach ($items as $item){
			$ad = $item['properties'];

			$alert_exp = strtotime($ad['expires']);
			if ($alert_exp < time()) continue;
			$alert=[];

			$alert['headline'] = $ad['headline'];
			$alert['category'] = $ad['category'];
			$alert['event'] = $ad['event'];
			$alert['expires'] = $ad['expires'];
			$alert['description'] = $ad['description'];
			$alert['instruction'] = $ad['instruction'];
			$alert['expire_ts'] = $alert_exp;

			$x[] =$alert;

		}
		$y[$src] = $x;
		Log::info('Updated galerts for alerts');
		$this->write_cache('galerts',$y);
//u\echor($y,'from external  alerts', NOSTOP);
	return $y;
}


private function rebuild_cache_wgalerts() {
/*
alerts used only in galerts. No cache saved.

https://api.weather.gov/alerts/active/zone/{zoneId}

2zones:
Lost Horse (west JtNP) zoneid=CAZ560
JTNP East CAZ561
JTNP CAZ230
Morongo basin CAZ525
Coachella valley CAZ061
PSP CAZ261
Yucca CAZ228
Salton CAZ563
Siskiyou CAZ285
PDX ORZ006

*/
	$x=[];
	$src = 'wgalerts';
	$zone = 'CAZ285'; #joshua tree np
#	$zone = 'ORZ006'; #pdx
#	$zone = 'CAZ082'; #shasta
#	$zone = 'COZ040'; #denver

	$curl_header = [];
	$url = "https://api.weather.gov/alerts/active/zone/$zone";
		$expected = '';
		$loginfo = "$src: zone $zone";
		if (!$aresp = $this->get_curl($loginfo,$url, $expected, $curl_header) ) return false;
		$x = $aresp;

	//$this->write_cache($src,$x);
	return $x;
}

public function rebuild_properties() {
	$locs=['br','cw','jr','pdx','shasta'];
	$x=[];
	$x['update'] = time();
	$src = 'properties';
	foreach ($locs as $loc){
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];
		$loginfo = "$src: $loc";
		$url = "https://api.weather.gov/points/$lat,$lon";
					//(https://api.weather.gov/points/{lat},{lon}).
		$expected = 'properties';
		if (!$aresp = $this->get_curl($loginfo,$url, $expected, $curl_header) ) {sleep (2); #retry
			if (!$aresp = $this->get_curl($loginfo,$url, $expected, $curl_header) ) {
				return false;
			}
		}
		$x[$loc] = $aresp['properties'];
	}
	$this->write_cache($src,$x);
	Log::info('Rebuilt properites');
	return true;
}

public function set_properties (array $locs) {
	// gets meta data for each location by lat,lon
	// saves it in data file properties.json
	$src = 'props';
	$x = array('src'=>$src);
	$x['update'] = time();



	if ( ! $r = $this->get_external ($src,$locs)) return false;
	//u\echor($r,'From external',STOP);

	foreach ($r as $loc => $d){	//uses weather.gov api directly
		$y[$loc] = $d['properties'];
	} #end foreach
// u\echor($y,'properties',STOP);

	$this->write_cache('properties',$y);
	echo "Properties updated" . BRNL;
	return true;


}

// OBSOLETE??
public function Xrefresh_cache (string $cache ) {
	/* loads data from all source fgiles and rebuilds the cache fie compoetely.
	init is array of data to be set as inital conditions.
	= either default init or latest today array.
	*/


	// creates or updates the section's cache file
	//$v = array ('updated' => time());

	//echo " Refreshing $cache" . BRNL;

	// external $w
			switch ($cache) {
				case 'wapi':
					if (! $r = $this->get_external ($cache,$this->wlocs) ){
						// failed to get update.  Warn and go on
						// "Warning: attempt to reload $cache failed.";
						Log::notice("attempt to refresh $cache failed");
						return false;
					}
						Log::info("Cache $cache refreshed.");
				//	if (!$w = $this -> format_wapi($r) ){
				// 		echo "Warning: failed to parse data returned from $cache";
// 						return false;
// 					}
					$this->write_cache ($cache,$r);
					break;
				case 'airowm':
					if (! $r = $this->get_external ($cache,$this->airlocs) ){
						// failed to get update.  Warn and go on
						//echo "Warning: attempt to reload $src failed.";
						Log::notice("attempt to refresh $cache failed");
						return false;
					}
Log::info("Cache $cache refreshed.");
				//	if (!$w = $this -> format_airowm($r) ){
// 						echo "Warning: failed to parse data returned from $cache";
// 						return false;
// 					}
					$this->write_cache ($cache,$r);


					break;


				case 'properties':
					$plocs = ['jr','cw','hq','br','kv'];
					$w = $this->set_properties($plocs);
					if (!$w)die ("no properties");
					break;

				case 'wgova': // alerts
					if (! $r = $this->get_external ($cache,['hq']) ){
					//echo  "Warning: attempt to reload $cache failed.";
						return false;
					}
					Log::info("Cache $cache refreshed.");
					$this->write_cache ($cache,$r);
					break;

				case 'wgov': //weather
					if (! $r = $this->get_external ($cache,$this->wlocs) ){
						// failed to get update.  Warn and go on
	//					u\echor ($r,'in refresh cache');
// 						echo "Warning: attempt to reload $cache failed.";
		Log::notice("attempt to refresh $cache failed");
						return false;
					}
					Log::info("Cache $cache refreshed.");
				//	if (!$w = $this -> format_wgov($r) ){
// 						echo "Warning: failed to parse data returned from $cache";
// 						return false;
// 					}
					$this->write_cache ($cache,$r);
					break;
				case 'admin':
						$w = self::$dummy_today;
						$this->write_cache($cache,$w);
						break;

				case 'alerts':
						$w= $this->get_alerts();
						Log::info("Cache $cache refreshed.");
						$this -> write_cache($cache,$w);
						break;

				case 'airnow':
					if (! $r = $this->get_external ($cache,$this->airlocs) ){
						// failed to get update.  Warn and go on
// 						echo "Warning: attempt to reload $cache failed.";
					Log::notice("attempt to refresh $cache failed");
						return false;
					}
					Log::info("Cache $cache refreshed.");
					//if (!$w = $this -> format_airnow($r) ){
// 						echo "Warning: failed to parse data returned from $cache";
// 						return false;
// 					}
					$this->write_cache ($cache,$r);

					break;

				case 'airq':
					if (! $r = $this->get_external ($cache,$this->airlocs) ){
						// failed to get update.  Warn and go on
// 						echo "Warning: attempt to reload $cache failed.";
						Log::notice("attempt to refresh $cache failed");
						return false;
					}
					Log::info("Cache $cache refreshed.");
					//if (!$w = $this -> format_airq($r) ){
// 						echo "Warning: failed to parse data returned from $cache";
// 						return false;
// 					}
						$this -> write_cache($cache,$r);
						break;



				case 'admin':

					return true;
					break;

				case 'cgopen':
					return false;

					break;

				default: return false;
		}

	return true;
}

######### END CACHES ############

public function filter_calendar() {
	/*
		removes expired events from calendar and sort by date
		calenar = array (
			0 = array (dt,type,title,location,note),
			1 = ...
			);
	*/

	$z=[];

		$y = json_decode (file_get_contents(CACHE['calendar']), true);;

//  	u\echor($y,'cal loaded');

	// ignore invalid dt or dt older than now
	// set first term in if to 1 to prevent filtering
	foreach ($y as $cal){
		if ( 0 || (is_numeric($cal['dt']) && (time() < $cal['dt']) )){
			$z[] = $cal;
		}
	}
//  		u\echor($z,'cal filtered', STOP);
	if (!empty($z)){
		$z = u\element_sort($z, 'dt');
	}
	return $z;

}

public function update_section(string $section,array $u) {
		// reads section cache
		// merges in $u
		// saves to cache


		if (file_exists(CACHE[$section])){
			$y = json_decode(file_get_contents(CACHE[$section]), true);
		} else {
			$y = [];
		}

		$z = array_merge($y, $u);
//  		u\echor ($z,"Merged in Update $section", );
		$this->write_cache($section, $z);
		return true;
	}



########   LOAD ###############
#-----------------  LOAD today --------------------

// private function load_today() {
// 		$refresh = false;
// 		$section = 'today';
// 		if (! file_exists (CACHE[$section])) {
//
// 			$refresh = true;
// 		}
//
// 		if ($refresh) {
// 			$this->refresh_cache($section);
// 		}
//
// 		$y = json_decode (file_get_contents(CACHE[$section]), true);
// 		if (empty($y['camps'])){ #test fpr local stuff there
// // need to send an alert iuf this happens
// 			$y = self::$dummy_today;
// 		}
// // 	u\echor($y,'loaded today');
//
//
//
// // u\echor($y,'today after clean', STOP);
//
// 		$target_date = date('l, d M Y');
// 		$y['target'] = $target_date;
// 		$y['updated'] = date ('d M H:i');
//
// 		return $y;
//
//
//
//
// }

#-----------------  LOAD EXTERNASL --------------------

public function Xget_external (string $src,array $locs=['hq']) {
	/*
		supply one of the source codes ('wapi') and an array
		of at least one valid location (even if not used)

		will return array of key data from source urls for each locatrion
		[
			updated => time
			sourve => code
			data = [
				loc => response array,
				loc => response array,
			]
		]

		Routine retrieves data, retries if failure, and checks for
		one key array value that must be present at some level of the result.
		Set name of required field in "expected" in the location switch.
		If blank, no test will be made.
	*/


	$x=[];
	$src_name = $this->Defs->getSourceName($src);
//echo "src: $src_name" . BRNL;


	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];
		switch ($src) {
			case 'airq' :


				$expected = 'aqi'; #field to test for good result
				$curl_header = [
			"X-RapidAPI-Host: air-quality.p.rapidapi.com",
			"X-RapidAPI-Key: 3265344ed7msha201cc19c90311ap10b167jsn4cb2a9e0710e"
				];

				$url = "https://air-quality.p.rapidapi.com/current/airquality?lon=$lon&lat=$lat";
				$expected = '';

				break;

			case 'airowm':
				$url = "http://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid=" . $this->Defs->getKey('openweathermap');
				$expected = ''; #field to test for good result
				break;

			case 'airnow':
				$url = "https://www.airnowapi.org/aq/observation/latLong/current/?format=application/json&latitude=$lat&longitude=$lon&distance=25&API_KEY=" . $this->Defs->getKey('airnow');
				$expected = 'AQI'; #field to test for good result
				break;

			case 'wapi':
				$url = 'http://api.weatherapi.com/v1/forecast.json?key=' . $this->Defs->getKey('weatherapi') . '&q='. $this->Defs->getCoords($loc) . '&days=3&aqi=yes&alerts=yes';
					$expected = '';

					break;
			case 'wgov':
				$url = "https://api.weather.gov/gridpoints/" . $this->Defs->getGridpoints($loc) ."/forecast";
					$expected = 'properties';
				break;

			case 'props':
				$url = "https://api.weather.gov/points/$lat,$lon";
				//(https://api.weather.gov/points/{lat},{lon}).
				$expected = 'properties';
				break;

			case 'wgova':
				$url = 'https://api.weather.gov/alerts/active/zone/CAZ230';
				$expected = '';

				break;

			default: die ("Unknown source requested for external data: $src");

		}


// attempt to get data.  3 retries.
	if (!$aresp = $this->get_curl($src,$url, $expected, $curl_header) )
	{
		#Log::info("get_curl failed on $src, $url, $expected");
		return false;
	}
	$x[$loc] = $aresp;
	} # next loc

	return $x;
}
/*
'airq' => 'air-quality.p.rapidapi.com/current',
	'owm' => 'api.openweathermap.org',
	'now' => 'airnowapi.org/observation',
	'wapi' => 'api.weatherapi.com forecast',
*/

public function format_airq ( $r){

	$x=[];
	$x['update'] = time();


	foreach ($r as $loc => $d){
		$y['aqi'] = $d['data'][0]['aqi'];
		$y['pm10'] = $d['data'][0]['pm10'];
		$y['o3'] = $d['data'][0]['o3'];

		$x[$loc] = $y;
	}

	return $x;
}





public function format_airowm ($r){
	$x=[];
	$x['update'] = time();

	foreach ($r as $loc => $d){
			$aqi = $d['list']['0']['main']['aqi'];
			$aqi_scale = $this -> Defs->aq_scale($aqi);
			$aqi_color = $this -> Defs->scale_color($aqi_scale);

			$y['aqi'] = $aqi;
			$y['pm10'] = $d['list'][0]['components']['pm10'];
			$y['o3'] = $d['list']['0']['components']['o3'];
			$y['aqi_scale'] = $aqi_scale;
			$y['aqi_color'] = $aqi_color;
			$y['dt'] = $d['list']['0']['dt'];

			$x[$loc] = $y;
	}

	return $x;
}



public function format_airnow ($r){
		$x=[];
	$x['update'] = time();


/* uses airnow.org - referred from eps.gov
	current is good, but forecasts return empty.
	forecast at airnowapi.org/aq/forecast
	now at aq/observation/latlong/current

can get forecast for 29

*/

/*
Array
(
    [0] => Array
        (
            [DateObserved] => 2022-07-16
            [HourObserved] => 8
            [LocalTimeZone] => PST
            [ReportingArea] => Joshua Tree National Park
            [StateCode] => CA
            [Latitude] => 34.0714
            [Longitude] => -116.3906
            [ParameterName] => O3
            [AQI] => 64
            [Category] => Array
                (
                    [Number] => 2
                    [Name] => Moderate
                )

        )

)
*/

	foreach ($r as $loc => $d){

			$aqi = $d['0']['AQI'] ?? '' ;
				$aqi_scale = ($aqi)? $this -> Defs->aq_scale($aqi) : '';
				$aqi_color = ($aqi) ? $this -> Defs->scale_color($aqi_scale) : '';

			$y['aqi'] = $aqi;
			$y['pm10'] = $d['0']['PM10'] ?? 'n/a';
			$y['o3'] = $d['0']['O3']  ?? 'n/a';
			$y['aqi_scale'] = $aqi_scale;
			$y['aqi_color'] = $aqi_color;
			$y['observed_dt'] = strtotime($d[0]['DateObserved'] . ' ' . $d[0]['HourObserved'] . ':00') ;
			$y['reporting'] = $d[0]['ReportingArea'];

			$x[$loc] = $y;
		}



	return $x;

 }


private function getMtime($section){
	$mtime = filemtime (CACHE[$section]);
	return $mtime;
}

public function format_wapi ($r) {

	$x = [];
	$x['update'] = time();// will end up with $y[$src] = $x;

	foreach ($r as $loc => $ldata){
		 $forecast = $ldata['forecast']['forecastday'];
		 // there are forecasts for 3 days
		for ($i=0;$i<3;++$i){
			$daily = $forecast[$i]; #array

		//	echo "period: $period";

			$fdate = \DateTime::createFromFormat('Y-m-d', $daily['date']);
			$dayts = $fdate->format('s');

			$period = $daily['date'];

			$w[$loc][$i] = array(
				'epoch' => $daily['date_epoch'],
				'date' => $fdate->format('l, M j'),
				'High' => round($daily['day']['maxtemp_f']) ?? 'n/a',
				'Low' => round($daily['day']['mintemp_f']) ?? 'n/a' ,
			//	'winddir' => $daily['day']['winddir'],
				'avghumidity' => $daily['day']['avghumidity'],
				'maxwind' => round($daily['day']['maxwind_mph']),
				'skies' => $daily['day']['condition']['text'],
				'rain' => $daily['day']['daily_chance_of_rain'],
				'visibility' => $daily['day']['avgvis_miles'],
				'uv' => $daily['day']['uv']

				);
		} #end for day

	$x['forecast'] = $w;

	// add airquality current
	 $current_aq = $r[$loc]['current']['air_quality'];
	 $current_aq['updated_ts'] = $r[$loc]['current']['last_updated_epoch'];

	 $x['aq'][$loc] =  $current_aq ;
	} #end location

	// add astro and alerts for jr today

	$astro = $r['jr']['forecast']['forecastday']['0']['astro'];
	$dayuv = $r['jr']['forecast']['forecastday']['0']['day']['uv'];
	$light = array(
				'sunrise' => $this -> time_format( $astro['sunrise']),
				'sunset' => $this -> time_format($astro['sunset']),
				'moonrise' => $this -> time_format($astro['moonrise']),
				'moonset' => $this -> time_format($astro['moonset']),
				'moonillumination' => $astro['moon_illumination'],
				'moonphase' => $astro['moon_phase'],
				'uv' => $dayuv,
		);

	$x['light'] = $light;


	return $x;
}

public function wgov_to_forecast($weathergov) {
	/*
		manipulate data in wgov to create
		a forecast format easier to handle
	*/

		$locs = array_keys($weathergov);
	foreach ($locs as $loc) {
	//echo "loc $loc ";
		if (! $locname = $this->Defs->getLocName($loc) ){continue;}


		$fcarray = $weathergov[$loc]['properties']['periods'];
		$dayno = 0;
		foreach ($fcarray as $period) {
			$day = substr($period['startTime'],0,10);
			$period['dayts'] = strtotime($day);
			$period['locname'] = $locname;
			$fc[$loc][$day][] = $period; // so may be 1 or 2 per day
		}
	}
	return $fc;
}

private function format_alerts($r){
	if (empty($r)) return '';
	$t = $this->clean_text($r);
	$anlist = explode("\n",$t);
	foreach ($anlist as $al){

	}
	return $t;
}

public function format_wgov ($r) {

	$x=[];

	foreach ($r as $loc => $ldata){	//uses weather.gov api directly
		if (empty($x['update'])){
			$x['update'] = strtotime($ldata['properties']['updated']);
		}
		$periods = $ldata['properties']['periods'] ?? '';

		$day = 0;
		$lastday = '';
		foreach ($periods as $p){ // period array]	d
			// two periods per day, for day and night
			// put into one array
// u\echor($p,'period',NOSTOP);
	// set day (key) to datestamp for day, not hours
			$sttime = $p['startTime'];
			$highlow = $p['isDaytime']? 'High':'Low';
			$daytext = date('l, M d',strtotime($sttime));
			if ($daytext != $lastday){
				++$day;
				$lastday = $daytext;
			}
			$p['daytext'] = $daytext;
			$p['highlow'] = "$highlow ". $p['temperature'] . '&deg; F' ;

			$x[$loc][$day][] = $p;

		} #end foreach period

	} #end foreach location

	$weather['weather'] = $x;
	return $weather;
}





// -----------   UTILITY FUNCTIONS -------------

private function time_format($time) {
	// remove leading 0

	if (substr($time,0,1) == '0'){
		$time = substr ($time, 1);
	}

	return $time;
}

private function curl_options () {

	$agent = 'Mozilla/5.0 (NPS.gov/jotr app)';


	$options = [
	CURLOPT_USERAGENT => $agent,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",


	];

	return $options;
}




private function uv_data($uv) {
	// takes numeric uv, returns array of uv, name, warning
		$uvscale = $this -> Defs->uv_scale($uv);
		$uv = array(
			'uv' => $uv,
			'uvscale' => $uvscale,
			'uvwarn' => $this ->Defs->uv_warn($uvscale),
			'uvcolor' => $this->Defs->get_color($uvscale),
		);
			return ($uv);
}

private function fire_data($fire_level) {
	$fire = array (
		'level' => $fire_level,
		'color' => $this->Defs->get_firecolor($fire_level),
		);

	return $fire;
}
public function Xstart_page ($title = 'Today in the Park',$pcode='') {
	$scbody = '';
	$added_headers = "";
	if ($pcode=='s') {
		$scbody='onLoad="pageScroll()"';
		$added_headers = "<style>html {scroll-behavior: smooth;}</style>";
	}
	elseif ($pcode=='p'){
		$scbody = "onLoad='startRotation(10)'";
	}
	elseif ($pcode=='b'){
		$added_headers = <<<EOT
<!-- Latest compiled and minified CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
EOT;
}
	elseif ($pcode=='p'){ #print
		$added_headers = "<link rel='stylesheet' media='print' href = '/css/media.css' >";
	}
	$site_url = SITE_URL;
	$platform = '(' . PLATFORM .')';
	$text = <<<EOF
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>$title $platform</title>
	<script src='/js/snap.js'></script>
	<script src='/js/hide.js'></script>

	<link rel='stylesheet' href = '/css/main.css' />

	$added_headers

</head>
<body $scbody>
<table style='width:100%;border-collapse:collapse;'>
<tr style='background-color:black;text-align:right;color:white;'><td style='background-color:black;text-align:right;color:white;'>
Department of the Interior<br>
Joshua Tree National Park
<h1 style='margin:0'>Today in Joshua Tree National Park</h1>
</td><td style='width:80px;'>
<img src="$site_url/images/Shield-7599-alpha.png" alt="NPS Shield" />
</td></tr>
</table>


EOF;
	return $text;
}

private function split_coord ($loc) {
	if (!$coord = Defs::$coordinates[$loc]){
		die ("Attempt to get coordinates of undefined location $loc");
	}
	[$lat,$long] = explode(',',$coord);
	return [$lat,$long];
}

private function over_cache_time($section) {
	//global $Defs;
	if (!file_exists(CACHE[$section])){ return true;}

	$filetime = filemtime (CACHE[$section]);
	$limit = $this->Defs->getMaxTime($section);
	$diff = time() - $filetime;
	if ($limit && ($diff > $limit)) return true;
//	echo "$section: limit $limit; diff $diff;" . BR;
	return false;
}

private function str_to_ts($edt) {
			try {
				if (empty($edt)) return '';;
				if (! $t = strtotime($edt) )
					throw new RuntimeException ("Illegal date/time: $edt");
				return $t;
			} catch (RuntimeException $e) {
				u\echoAlert ($e->getMessage());
				echo "<script>history.back()</script>";
				exit;
			}
		}

public function write_cache(string $section,array $z) {
	if (empty($z)){
	trigger_error("Writing empty array to $section", E_USER_WARNING) ;

	}

	file_put_contents(CACHE[$section],json_encode($z));
	Log::info("Cache $section updated");
}

public function clean_text( $text = '') {
	// removes spec chars and changes nl to br
	if (empty($text)) return '';
	$t = htmlspecialchars($text,ENT_QUOTES);
	$t = nl2br($t);
	return trim($t);
}

function get_curl ($src, $url,string $expected='',array $header=[]) {
		/* tries to geet the url, tests for suc cess and
			for expected result if supplied.
			returns result array on success
			returns false on erro.
			$src is just for TLog info
		*/
		$curl = curl_init();
		$curl_options = $this->curl_options();
		curl_setopt_array($curl,$curl_options);
		curl_setopt($curl,CURLOPT_URL, $url);
		if ($header)
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$aresp = [];
		$success=0;
		$this->lock_cache();
		while (!$success) {
			static $tries =0;
			$fail = '';
			//u\echor($aresp,"Here's what I got for $src:");

			if ($tries > 2){
					//echo "Can't get valid data from ext source  $src";
				Log::notice("Curl failed for $src: $fail.");
				$this->unlock_cache($src);
				return [];
			}
			if (! $response = curl_exec($curl)) {
				$success = 0;
				$fail = "No curl responce on $src";
			}else { $success = 1;}


			if ($success && !$aresp = json_decode($response, true) ){
				$success = 0;
				$fail = " failed JSON decode ";
			}else { $success = 1;}

			if ($success &&  $expected && !u\inMultiArray($expected,$aresp)) {
				$success = 0;
				$fail = "failed expected result $expected";
			}else { $success = 1;}

			if (! $success) {
					++$tries;
					sleep (10);

			} else {
				$this->unlock_cache($src);
				curl_close($curl);
				return $aresp;
			}

		}

	}


public  function print_pdf (string $html, $pdffile){
// pdffile is path relative to public folder, i.e., pages/file.pdf
if (empty($html)) die ("no html to print_pdf");

	// echo $Today->start_page('test page','p');
// 	$z = $Today -> prepare_today();
// 	$out =  $Plates->render('today-print',$z);
// 	file_put_contents(REPO_PATH . '/public/pages/print.html' , $out);

	$headers = array();
	$headers[] = 'project: OSyxsT8B8RC83MDi';
	$headers[] = 'token: 0gaZ43q1NHn9Wj8NdCL7WetJvKj7vIv8bAHQpn8JPqz909nPOzU5eetM8u0v';
	$headers[] = "Content-Type: text/html";
	$headers[] = "Accept: application/pdf";


#	$data = "@pages/print.html";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://api.typeset.sh");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$html);


	$resp = curl_exec($ch);

	curl_close ($ch);


	file_put_contents(SITE_PATH . $pdffile,$resp);

//$output = `curl -d @pages/print.html -H 'project: OSyxsT8B8RC83MDi' -H 'token: 0gaZ43q1NHn9Wj8NdCL7WetJvKj7vIv8bAHQpn8JPqz909nPOzU5eetM8u0v' -X POST https://api.typeset.sh/ > pages/print.pdf 2>&1"`;




}

} #end class
