<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	//require_once $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Log;



//END START

// u\echor (CACHE); exit;

/* TOPICS

This system collects data from a number of sources and stores it
in a series of caches, one for each data source.  The data is
stored in json files in directory var/.

Various output reports present the ddata in different forms,
typically by retrieving the data from cache, reformatting it,
and the echoing it through a template. (Plates is the chosen t
emplate system.)

A Definitiions file includes many static parameters,
including a list of the acceptable age for each cache file.
Periodically (say every 4 hours) a routine is run which tests each
cache for being out of date, and if so, refreshes it.

Caches are gfenerally filled by a curl command to an api with a key.

One cache is admin data, which is created from a web form on the
admin page.  This includes alerts, closures, campground status, etc.

Once cache is the calendar, which is also maintained through the admin
page, but might at some point be derived from the park calendar on
the NPS site.

Other ccaches are for weather.gov, weatherapi.com, airnow.com,

Campsite open sites can be entered manually on the admin page, but
is intended to be filled automatically from recreation.gov  Campsite opens
are sotred in their own cache to facilitate updating through a script.

CACHES
admin	Contains notices, alerts, a pithy statement

wgov	contains forecasts for today and next 2 days (3 days) for each designated location: geneally jumbo, 29, cottonwood and black rock.

wapi is similar, but from a different source. 9weatherapi.com)
air	air quality info for jumbo rocks

camps status for each campground.

cgopen conatins open sites at each campground

light	containx sunrise, moon phase, etc.  Derivedd from weatherapi.com
astro data

Display is generally by retrieving a cache, manipulating the data (format_wgov, e.g.,) then displaying through Plates template.

The sytstem was originally designed to support rotating TV displays in
visitor ceneters.  It does that by assign page divs in the html,
and using a javascript to successively enable and disable display of
the divs.  So the apparent rotation is just enabling adn disabling display
or successive divs in the code.  The page does not need to be reaccessed
for this.  However the pages have a meta refresh tag for 15 minutes, to
cause a refresh from server every 15 minutes. This will pick up any new
data stored in caches, such as an alert or a change in weather forecast.

The TV page also adds a new style setting the base font size (html {} )
to 24 pt and line height to 1.1em.

A refresh_cache function checks each cache for age as defined in Defs, and
if it is out of date reloads it from source,  (Caches that aren't
auto refreshed have age limit in Defs set to 0).  refresh_cache is run from
cron, typically ever 4 hours.  When caches are reloaded, sometimes the
load failsk especially on weather.gov.  If there's a failure, there are two
retries with 2 second delays, and if 3rd attempt fails the refresh is
abandoned and the cache is not changed.  For weather, each location
(jumbo rocks, cottonwood, black rock, etc) is a separate request. If one
fails, they are all abandoned,



*/
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

private static $empty_opens = array(
	"ic" => "20","jr" => "0","sp" => "0","hv" => "0","be" => "0","wt" => "0","ry" => "0","br" => "0","cw"=>"0"
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
	$this->cache_lock = REPO_PATH . "/var/cache.lock";



}


public function build_topics(){
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
			$this->build_topic_admin(),
			$this->build_topic_weather(),
			$this->build_topic_campgrounds(),
			$this->build_topic_light(),
			$this->build_topic_air(),
			$this->build_topic_calendar(),
			$this->build_topic_current(),




		);


//	u\echor($topics,'topics',STOP);

		return $topics;
}


public function prepare_admin() {
// get sections needed for the admin form


	if (!$y['admin'] = $this->load_cache('admin')){
	 	Log::error ("Could not load cache admin");
	 	exit;
	 	return [];
	 }
// 	u\echor ($y, 'read admin cache', NOSTOP);

	// set firelevel options array
	$fire_levels = array_keys(Defs::$firewarn);
	$y['admin']['fire_level_options'] = u\buildOptions($fire_levels,$y['admin']['fire_level']);

// camps
	foreach (array_keys(Defs::$campsites) as $cgcode){
		$opt = u\buildOptions(Defs::$cgstatus, $y['admin']['cgstatus'][$cgcode] ?? '');
		$opts[$cgcode]  = $opt;
		$notes[$cgcode] = $y['admin']['cgnotes'][$cgcode] ?? '';

	}
	$rchecked = [];
	$rotators = $y['admin']['rotate'] ?? [];
	foreach (array_keys(Defs::$rpages) as $pid){

		if (in_array($pid,$rotators)){$rchecked[$pid] = 'checked';}
	}
	$y['admin']['cg_options'] = $opts;
	$y['admin']['cg_notes'] = $notes;
	$y['admin']['rchecked'] = $rchecked;
	$y['admin']['cgsites'] = array_merge($this->load_cache('cgopen'), $this->load_cache('cgres'));
	//$y['admin']['cgfull'] =  (!array_filter($y['admin']['cgopen'])) ? 1:0;
	//$y['admin']['cgres'] = $this->load_cache('cgres') ?? [];


	$calendar = $this->Cal->filter_calendar($this->load_cache('calendar'),0);

#add 3 blank recordsw
	for ($i=0;$i<3;++$i) {
		$calendar[] = $this->Cal::$empty_cal;
	}

$calendar = $this->Cal->add_types($calendar);
$y['calendar'] = $calendar;


	// if (! $galerts = $this->rebuild_cache_galerts() ){
// 	}

	if (!$y['galerts'] = $this->load_cache('galerts') ){
	 	Log::error ("Could not load cache galerts");
	 	return [];
	 }



// u\echor ($y, 'Y to admin',NOSTOP);
	return $y;
}

public function load_cache ($section) {

#echo "loading cache $section" . BR;
		if (!file_exists (CACHE[$section])) {
			Log::notice ("Cache $section does not exist. Refreshing.");
			die ("Attempt to read non-existent cache $section");
		}
		$ot = round($this->over_cache_time($section)/60); //in minute
		if ( $ot > 90) {
			Log::info("Loading stale cache $section: $ot minutes");

		}

		if (!$y = json_decode ($this->file_get_contents_locking(CACHE[$section]), true)) {
			Log::error("Failed to load $section cache");
			return [];
		}
		//u\echor($y,$section,STOP) . BR;
		return $y;
}


public function post_admin ($post) {
 /* insert posted data and dependencies into cacjes


*/
 u\echor ($post, 'Posted');

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
	$y['uncertainty'] = $post['uncertainty']; #hours to keep site avail
	$y['rotate'] = $post['rotate']; //array
//u\echor($y,'y',STOP);
	$this -> write_cache('admin',$y);

	$cgo = $post['cgupdate'];
	u\echor ($cgo,'cgupdate from post');
	// remove any enbtries with blank avlues
	$cgo = array_filter($cgo,function ($val) {return ($val !== '' );});
	//u\echor ($cgo,'cgupdate after filter');
	$cgopen = [];
	$cgres = [];


	foreach ($cgo as $cg=>$open){
		if ($post['cgstatus'][$cg] == 'Reservation'){
			$cgres[$cg] = $open;
		} elseif ($post['cgstatus'][$cg] == 'First'){
			$cgopen[$cg] = $open;
		}
	}
	// overwrite existing data with updates
	$this->mergeCache('cgopen',$cgopen);
	$this->mergeCache('cgres',$cgres);



	$this->Cal->post_calendar($post['calendar']);


}

public function mergeCache($cache,$merge){
// merges data into cache, unless data is empty
		$x = $this->load_cache($cache) ;
//		u\echor ($x, "merge: Loaded cache $cache");
//		u\echor ($merge,'merge: Data to merge');
		if (! empty ($merge)){ #if empty you're done
			$y = array_merge($x,$merge);
// 		u\echor ($y,'merged cache');
			$this->write_cache($cache,$y);
		}
	}


public function buildPDF(){
	$y = $this->prepare_topics ();
//u\echor($y,'y',STOP);

// using "Today' as title prevents it from re-appearing on the today page.
$meta=array(
	'pcode' => 'print',
	'title'=>'Today',
	'target'=> $y['target']?? '',
	'pithy'=> $y['pithy'] ?? '',

	);

	$html = $this->Plates->render ('start',$meta);

//	echo $Today->start_page('Today in the Park',$qs);
	$html .= $this->Plates -> render('today',['data'=>$y]) ;
	file_put_contents( SITE_PATH . '/pages/print.html', $html);
	$this_day = date('m-d-y');
	// make a pdf version if none exists.  This limits to 1 per day.
	$pdf = '/pages/' . "${this_day}.pdf";
	if (!file_exists(SITE_PATH . $pdf)){
		$this->print_pdf($html,$pdf);
	}
}
/*----------------- BUILD TOPICS ------------------*/

public function build_topic_calendar() {
	if (!$z=$this->load_cache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }

#	u\echor($z,'calendar',STOP);
//	$y=$this->Cal->filter_calendar($z,0);
	return ['calendar' => $z];
}
public function build_topic_current() {
	if (!$z=$this->load_cache('current')) {
	 	Log::error ("Could not load cache current");
	 	return [];
	 }

	$y=$z['lhrs']['properties'];
	$y['updatets'] = strtotime($z['lhrs']['properties']['timestamp']);
	$y['temp_c']= is_null($y['temperature']['value']) ? 'n/a' : round($y['temperature']['value'],1);
	$y['temp_f'] = is_null($y['temperature']['value']) ? 'n/a' :round( ($y['temp_c'] * 9/5) + 32);
	$y['wind_kph'] = is_null($y['windSpeed']['value'])? 'n/a':round($y['windSpeed']['value']);
	$y['wind_mph'] =  is_null($y['windSpeed']['value'])? 'n/a':round($y['wind_kph'] /2.2) ;
	$y['wind_direction'] = $this->degToDir($y['windDirection']['value']??0);

// u\echor($y,'current', NOSTOP);
	return ['current' => $y];
}

public function degToDir($deg) {
	if ($deg <= 22.5) return 'N';
	if ($deg <= 67.5) return 'NE';
	if ($deg <= 112.5) return 'E';
	if ($deg <= 157.5) return 'SE';
	if ($deg <= 202.5) return 'S';
	if ($deg <= 247.5) return 'SW';
	if ($deg <= 292.5) return 'W';
	if ($deg <= 337.5) return 'NW';
	if ($deg <= 382.5) return 'N';
}
public function build_admin_calendar() {
	if (!$z=$this->load_cache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }

#	u\echor($z,'calendar',STOP);
	$y=$this->Cal->filter_calendar($z,0);
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

//	u\echor($zz,'formatted wapi', STOP);

	$z['light']= $zz['light'];
	$z['light']['moonpic'] = $this->Defs->getMoonPic($z['light']['moonphase']);

	$z['uv'] = $this->uv_data($z['light']['uv']);

	return ['light' => $z];
}


public function build_topic_weather() {
	if (!$w = $this->load_cache('wgov') ){
	 	Log::error ("Could not load cache wgov");
	 	return [];
	 }
	$z['wgov'] = $this->format_wgov($w);
#u\echor($z,'formatted wgov');

	//get current temp
	$w = $this->load_cache('wapi');
	$z['wapi'] = $this->format_wapi($w);

//	u\echor($z,'weather topic');
	return $z;
}

public function build_topic_campgrounds() {
/* reads the res and open caches for available
	sites at each campgrund.  Prepares a display
	for each site based on campground status
	(.e., closed) and age of cache (changes to
	'?' if cache is too old.
*/

// admin cache contains status and notes for each cg
	if (!$y = $this->load_cache('admin') ){
	 	Log::error ("Could not load cache admin");
	 	return [];
	 }
//u\echor($y, 'loaded admin cache');

	$w['camps']['cg_notes'] = $y['cgnotes'];
	$w['camps']['cg_status'] = $y['cgstatus'];
	$w['camps']['cgfull'] = $cgfull = $y['cgfull'] ?? false;


// get age of each cache.
	$cgopen_age = $this->getMtime('cgopen');
	$cgres_age = $this->getMtime('cgres');
	$w['camps']['cgopen_age'] = $cgopen_age;
	 $w['camps']['cgres_age'] = $cgres_age;
	//$cg_uncertain = 0; // hours until display changes to ? (0 disables test)
	$uncertainty = $y['uncertainty'] ?? 0;

	// load the two caches and set display
	if (! $cgsites = array_merge($this->load_cache('cgopen'),$this->load_cache('cgres'))){
	 	Log::error ("Could not load cache cgopen or cgres");
	 	die();
	 }
	//set display based on status and age
	 foreach ($cgsites as $cg=>$open){ // ic => 7
	 	$status = $w['camps']['cg_status'][$cg];
		$display = $this->getCgDisplay($status,$open,$cgfull,$cgopen_age,$cgres_age,$uncertainty);
		$w['camps']['sites'][$cg] = $display;
	}



//u\echor($w['camps'], 'camps', NOSTOP);
	return $w;
}


private function getCgDisplay($status,$open,$cgfull,$cgopen_age,$cgres_age,$cg_uncertain) {
		if ($status == 'Closed'){return 'n/a';}
		if ($cgfull) {return '0';}
		if ($status == 'Reservation'){
			if ($cg_uncertain && (time() - $cgres_age > $cg_uncertain * 60 * 60)){return '?';}
			return $open;
		} elseif ($status == 'First') {
			if ($cg_uncertain && (time() - $cgopen_age > $cg_uncertain  * 60 * 60)){return '?';}
			return $open;
		} else {
			die ("Unknown cg status $status");
		}
	}

public function build_topic_admin() {
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
			//u\echor ($z['notices'],'build topic general',STOP);

			$fire_level = $y['fire_level'];
			$z['fire']['level'] = $fire_level;
			$z['fire']['color'] = Defs::get_firecolor($fire_level);

			$z['version'] = $this->file_get_contents_locking(REPO_PATH . "/data/version") ;
			$z['target'] = date('l F j, Y');

			$z['advice'] = $this->clean_text($y['advice']);
			$z['rotate'] = $y['rotate'] ?? [];

	//u\echor($z,'topic general', NOSTOP);
	return ['admin'=>$z];

}


########  CACHES #############
public function refresh_caches($force=false) {
Log::info ("Starting cache refresh cycle");

/* refreshes all the external caches, if they are due
	over-cache_time returns 5 minutes less than time set in defs,
	so caches refresh on a 1 hour interval
*/

		if ($this->over_cache_time('wapi') > 0 || $force) {
				$this->rebuild_cache_wapi();

		}
		if ($this->over_cache_time('airq') > 0|| $force) {
			$this->rebuild_cache_airq();

		}
		if ($this->over_cache_time('airowm')> 0 || $force) {
			Log::error("airowm updating because " . $this->over_cache_time('airowm') );
			$this->rebuild_cache_airowm();
		}
		if ($this->over_cache_time('wgov')> 0 || $force) {
				$this->rebuild_cache_wgov();
		}
		if ($this->over_cache_time('airnow')> 0 || $force) {
				$this->rebuild_cache_airnow();
		}
		if ($this->over_cache_time('galerts')> 0 || $force) {
				$this->rebuild_cache_galerts();
		}
		if ($this->over_cache_time('current') > 0 || $force) {
			$this->rebuild_cache_current();
		}
		if ($this->over_cache_time('calendar') > 0 || $force) {
			$this->rebuild_cache_calendar(); #filter out old stuff
		}
		if ($this->over_cache_time('cgopen') > 0 || $force) {

			//$this->rebuildCGopen(); #filter out old stuff
		}


			#	$this -> rebuild_properties('jr');
Log::info ("Completed cache refresh cycle");
}

// Rebuild caches goes to external and downloads data for each location
// if any location fails, the rebuild is aborted and new cache is not written,
// so old cache is retained.
// Could try to merge old and new, so only mssing location gets retained, but
// seems to complex right now.

public function rebuild_cache_wapi(array $locs=[] ) {
	$x=[];
	$src = 'wapi';
	if (empty($locs)){$locs = $this->wlocs;}

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = 'http://api.weatherapi.com/v1/forecast.json?key=' . $this->Defs->getKey('weatherapi') . '&q='. $this->Defs->getCoords($loc) . '&days=3&aqi=yes&alerts=yes';
	//echo "url: $url". BRNL;
		$expected = '';
		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		} //if one loc fails, fail the whole thing

		$x[$loc] = $aresp;
	} # next loc

	$this->write_cache($src,$x);
	Log::info("Saved updated cache $src");
	return $x;
}

private function rebuild_cache_calendar () {
	$x = $this->load_cache('calendar');
	$x = $this->Cal -> filter_calendar($x,0);
	$this->write_cache('calendar',$x);
	Log::info('rebuilt calendar cache');
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
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		} //if one loc fails, fail the whole thing
		$x[$loc] = $aresp;
	} # next loc

	$this->write_cache($src,$x);
	Log::info("Saved updated cache $src");

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
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		} //if one loc fails, fail the whole thing

		$x[$loc] = $aresp;
	} # next loc

	$this->write_cache($src,$x);
	Log::info("Saved updated cache $src");

}

public function rebuild_cache_current ($locs=[]) {
	/* latest data from LHRS
	curl -X GET "https://api.weather.gov/stations/LTHC1/observations/latest" -H "accept: application/geo+json"
	*/
	$src = 'current';
	if (!$locs) $locs = Defs::$clocs;

	$curl_header = [];
	foreach ($locs as $loc) {
		$url = "https://api.weather.gov/stations/LTHC1/observations/latest" ;
		#$url = "https://api.weather.gov/points/$lat,$lon";
		$expected = 'properties';

		$loginfo = "$src $loc";
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		}
		//u\echor($aresp,'current aresp');
		if (is_null($aresp['properties']['temperature']['value'] )) {
				Log::warning ("Received null temp for $loginfo",$aresp['properties']['temperature']);
				return [];
		}

		$x[$loc] = $aresp;
	} #next loc
	Log::info("Saved updated cache $src");
	$this->write_cache($src,$x);
	return $x;
}


public function rebuild_cache_wgov(array $loc = []) {
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
	$locs ??= $this->wlocs;
	#$locs = ['br'];

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = "https://api.weather.gov/gridpoints/" . $this->Defs->getGridpoints($loc) . '/forecast' ; #./forecast
		#$url = "https://api.weather.gov/points/$lat,$lon";
		$expected = 'properties';

		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		} //if one loc fails, fail the whole thing

		$x[$loc] = $aresp;
	} # next loc
	Log::info("Saved updated cache $src");
	$this->write_cache($src,$x);
	return $x;
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
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		} //if one loc fails, fail the whole thing

		$x[$loc] = $aresp;
	} # next loc

	$this->write_cache($src,$x);
	Log::info("Saved updated cache $src");

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
		Log::info('Rebuilt galerts using wapi');
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
		Log::info('rebuilt galerts using wgovalerts');
		$this->write_cache('galerts',$y);
//u\echor($y,'from external  alerts', NOSTOP);
	return $y;
}

public function rebuildCGopen($cycle) {
	/* reset cgopen in evening
		Retrieve data from rec.gov in morning
		call with cycle = am or pm
		return true if data updated; false if unchanged.
	*/
		if (!$opens = $this->load_cache('cgopen')) {
			Log::error('Rebuild cgopen failed; cannot open cache');
			return false;
		}

		if ($cycle == 'am') {
			if (!0 &&  $new_opens = $this->loadRecData() ){
				Log::info("Cannot retrieve opens from rec.gov");
				// do leave for another time.
				return false;
			}
			// update opens from fretried data
			$this->write_cache('cgopens',$new_opens);
			return true;
		} elseif ($cycle == 'pm'){
			// set all camp data to uknown
			$new_opens = [];
			foreach (array_keys(self::$empty_opens) as $cg){
				$new_opens[$cg] = '?'; // now unknown
			}
			$this->write_cache('cgopens',$new_opens);
			return true;
		} else {
			Log::error ("Called rebuild cgopen with invalid cycle: $cycle");
			return false;
		}
}

private function loadRecData(){
	//tbd
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
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) return false;
		$x = $aresp;
	Log::info("Retrieved cache $loginfo");

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
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {sleep (2); #retry
			if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
				return false;
			}
		}
		$x[$loc] = $aresp['properties'];
	}
	$this->write_cache($src,$x);
	Log::info("Retrieved locs for cache $src");
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


######### END CACHES ############





#-----------------  LOAD EXTERNASL --------------------


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
			$y['airwarn'] = $this->Defs->getAirWarn($aqi_scale);

			$x[$loc] = $y;
		}



	return $x;

 }



public function format_wapi ($r) {

	$x = [];
	$x['update'] = time();// will end up with $y[$src] = $x;
//u\echor($r,'R',STOP);

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
				'date' => $fdate->format('l, F j'),
				'High' => round($daily['day']['maxtemp_f']) ,
				'HighC' => round($daily['day']['maxtemp_c']) ,
				'Low' => round($daily['day']['mintemp_f']) ?? 'n/a' ,
				'LowC' => round($daily['day']['mintemp_c']) ?? 'n/a' ,
			//	'winddir' => $daily['day']['winddir'],
				'avghumidity' => $daily['day']['avghumidity'],
				'maxwind' => round($daily['day']['maxwind_mph']),
				'maxwindM' => round($daily['day']['maxwind_kph']),

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
	$x['current'] = $r['jr']['current']; // is atually for 29palms
//u\echor($x,'x',STOP);
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
			$tempc = round(($p['temperature'] -32 )* 5/9,0);
			$daytext = date('l, M d',strtotime($sttime));
			if ($daytext != $lastday){
				++$day;
				$lastday = $daytext;
			}
			$p['daytext'] = $daytext;
			$p['highlow'] = "$highlow&nbsp;". $p['temperature']. "&deg;F (" .$tempc . "&deg;C)" ;

			$x[$loc][$day][] = $p;

		} #end foreach period

	} #end foreach location

	 $x;
	return $x;
}





// -----------   UTILITY FUNCTIONS -------------

private function time_format($time) {
	// remove leading 0

	if (substr($time,0,1) == '0'){
		$time = substr ($time, 1);
	}
	$time=str_replace(' AM','&nbsp;AM',$time);
	$time=str_replace(' PM','&nbsp;PM',$time);

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

private function file_put_contents_locking($filename, $string)
{
    return file_put_contents($filename, $string, LOCK_EX);
}

private function file_get_contents_locking($filename)
{
    $file = fopen($filename, 'rb');
    if ($file === false) {
        return false;
    }
    $lock = flock($file, LOCK_SH);
    if (!$lock) {
        fclose($file);
        return false;
    }
    $string = '';
    while (!feof($file)) {
        $string .= fread($file, 8192);
    }
    flock($file, LOCK_UN);
    fclose($file);
    return $string;
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
	/* dies if file not exists
		0 if mtime is under the limit
		diff if mtime is over the limit by diff
		Returns true if time is within 5 minutes of limit
	*/
	if (!file_exists(CACHE[$section])){ die ("No cache file for $section");}
	$limit = $this->Defs->getMaxTime($section) ; #in seconds
	if ($limit){
		$filetime = filemtime (CACHE[$section]);
		// is filetime within an hour of limit
		$age = time() - $filetime;
		if ( $age > ($limit - 3500) ) return $age; #in seconds
	}
//	echo "$section: limit $limit; diff $diff;" . BR;
	return 0;
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
	Log::error("Writing empty array to $section") ;
	}
	if($this->file_put_contents_locking (CACHE[$section],json_encode($z))){
		//Log::info("Writing cache $section");
	} else {
		Log::error("Cannot write cache $section due to lock");
		//die("Error: cannot write $section due to lock file");
	}
}

private function getMtime($section){
	$mtime = filemtime (CACHE[$section]);
	return $mtime;
}

public function clean_text( $text = '') {
	// removes spec chars and changes nl to br
	if (empty($text)) return '';
	$t = htmlspecialchars($text,ENT_QUOTES);
	$t = nl2br($t);
	return trim($t);
}

function get_external ($src, $url,string $expected='',array $header=[]) {
		/* tries to geet the url, tests for suc cess and
			for expected result if supplied.
			returns result array on success
			returns false on erro.
			$src is just for Log info
		*/
		$curl = curl_init();
		$curl_options = $this->curl_options();
		curl_setopt_array($curl,$curl_options);
		curl_setopt($curl,CURLOPT_URL, $url);
		if ($header)
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$aresp = [];
		$success=0;
	// No .. dont need to lock out reads while getting data; only when writing
		$fail = '';	$tries = 0;
		while (!$success) {
			//u\echor($aresp,"Here's what I got for $src:");
			if ($tries > 2){
					//echo "Can't get valid data from ext source  $src";
				Log::notice("Curl failed for $src: $fail.",$aresp);
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
				curl_close($curl);
				Log::info ("External received for $src.  Tries $tries.");
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

####################OBSOLETE ###########################


} #end class
