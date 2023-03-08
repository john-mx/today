<?php
namespace DigitalMx\jotr;


use DigitalMx\jotr\Log;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;




//END START

// Utilities::echor (CACHE); exit;

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

A refresh_cache function checks each cache for age as defined
in CacheSettinigs, and
if it is out of date reloads it from source,  (Caches that aren't
auto refreshed have age limit in Defs set to 0. Caches that are
always refreshed have their age set to -n.  refresh_cache is run from
cron, typically ever half hour.  When caches are reloaded, sometimes the
load fails especially on weather.gov.  If there's a failure, there are two
retries with 2 second delays, and if 3rd attempt fails the refresh is
abandoned and the cache is not changed.  For weather, each location
(jumbo rocks, cottonwood, black rock, etc) is a separate request. If one
fails, the previous data is retained (new data is merged with old, not replaced),



*/

class DisplayManager {

private $Plates;
private $Defs;
private $CM;
private $Cal;
private $Camps;

private $light;
public $sunset; // set by prepare_topic_light;;

private $wlocs = ['jr','cw','br','hq'] ; // weather locations
private $airlocs = ['jr','cw','br']; // air quality locations

###############################

public function __construct($c){
	$this->Plates = $c['Plates'];
	$this-> CM = $c['CacheManager'];
	$this->Cal = $c['Calendar'];
	$this->Camps = $c['Camps'];

}

public function getTopics($list=[]) {

	$topicA = [];
		if (in_array('admin',$list))
			$topicA = array_merge($topicA,$this->build_topic_admin());
		if (in_array('weather',$list))
			$topicA = array_merge($topicA,$this->build_topic_weather());
		if (in_array('camps',$list))
			$topicA = array_merge($topicA,$this->build_topic_campgrounds());
		if (in_array('light',$list))
			$topicA = array_merge($topicA,			$this->build_topic_light());
		if (in_array('air',$list))
			$topicA = array_merge($topicA,			$this->build_topic_air());
		if (in_array('cal',$list))
			$topicA = array_merge($topicA,			$this->build_topic_calendar());
		if (in_array('current',$list))
			$topicA = array_merge($topicA,			$this->build_topic_current());
		if (in_array('uv',$list))
			$topicA = array_merge($topicA,			$this->build_topic_uv());
		if (in_array('fees',$list))
			$topicA = array_merge($topicA,			$this->build_topic_fees());
		if (in_array('npscal',$list))
			$topicA = array_merge($topicA,			$this->build_topic_npscal());
		if (in_array('galerts',$list))
			$topicA = array_merge($topicA,			$this->build_topic_galerts());

	return $topicA;
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
			$this->build_topic_uv(),
			$this->build_topic_fees(),
// 			$this->build_topic_npscal(),
			$this->build_topic_galerts(),

		);

		$this->Plates->addData($topics,
		[
			'today','light','notices','conditions','advice','weather',
			'campground', 'summary', 'rotate','campground-tv',
			'alerts','summary','weather','weather-wgov',
			'weather-one-line','weather-tv','calendar',

		]);



//	Utilities::echor($topics,'topics',STOP);

		return $topics;
}

public function showLight() {
	$meta = [];
	// $meta=array(
// 	'qs' => $qs,
// 	'page' => basename(__FILE__),
// 	'subtitle' => TODAY,
// 	'extra' => "<link rel='stylesheet' href='/css/tv.css'>",
// 	'rotate' => $rotate,
// 	'rdelay' => $rdelay,
// 	'sunset' => $this->sunset,
// 	'local_site' => $local['local_site'] ?? '',
//
// 	);

		$data = $this->build_topic_light();
		echo $this->startHTML($meta);
		echo "<body>Hellow</body></html>"; exit;
		$this->Plates->render('title',$meta);
		$this->Plates->render('light',$data);




}





/*----------------- BUILD TOPICS ------------------*/

public function build_topic_calendar() {
	if (!$cal=$this->CM->loadCache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }
	 // fix format change
	//if (! isset($cal['events'])) $cal['events']=$cal;

	 if (!$npscal=$this->CM->loadCache('npscal')) {
	 	Log::error ("Could not load cache npscal");
	 	return [];
	 }
	 $tags = $cal['npstags'];
	 $newcal = [];
	 // got through each nps event, and add tags if matching id
	 foreach ($npscal['npscal'] as $npsevent){
		$id = $npsevent['npsid'];
		if (isset ($tags[$id])){
			$npsevent = array_merge($npsevent,$tags[$id]);
		}

		$newcal[] = $npsevent;
	 }
	$z['events'] = array_merge($cal['events'],$newcal);
//   	U::echor($z,'merged cal',STOP);

	return ['calendar' => $z];
}

public function build_topic_current() {
	if (!$z=$this->CM->loadCache('current',false)) {
	 	Log::error ("Could not load cache current");
	 	return [];
	 }

	$y=$z['current']['lhrs']['properties'];
	$y['updatets'] = strtotime($z['current']['lhrs']['properties']['timestamp']);
	$y['temp_c']= is_null($y['temperature']['value']) ? 'n/a' : round($y['temperature']['value']);
	$y['temp_f'] = $temp_f = is_null($y['temperature']['value']) ? 'n/a' :round( ($y['temp_c'] * 9/5) + 32);
	$y['wind_kph'] = is_null($y['windSpeed']['value'])? 'n/a':round($y['windSpeed']['value']);
	$y['wind_mph'] = $wind_mph =  is_null($y['windSpeed']['value'])? 'n/a':round($y['wind_kph'] /1.6) ;

	$y['gusts_kph'] = is_null($y['windGust']['value'])? 'n/a':round($y['windGust']['value']);
	$y['gusts_mph'] =  is_null($y['windGust']['value'])? 'n/a':round($y['gusts_kph'] /1.6) ;

	$y['humidity'] = is_null($y['relativeHumidity']['value'])? 'n/a':round($y['relativeHumidity']['value']);

	$y['wind_direction'] = $this->degToDir($y['windDirection']['value']??0);

	$y['wind_chillC'] = is_null($y['windChill']['value']) ?
		'n/a' : round($y['windChill']['value']);
	$y['wind_chillF'] = is_null($y['windChill']['value']) ?
		'n/a' : $y['wind_chillC'] * 9/5 + 32;


	$wapi = $this->CM->loadCache('wapi')['wapi'];
	$current_uv = $wapi['jr']['current']['uv'];
	$y['uv'] = $this->uv_data($current_uv);

// Utilities::echor($y,'current', STOP);
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

public function build_topic_uv() {
	$y = $this->CM->loadCache('wapi')['wapi'];
//Utilities::echor ($y);
	$uv = $y['jr']['forecast']['forecastday'][0]['day']['uv'];
	$uvdata=$this->uv_data($uv);
	return ['uvdata'=>$uvdata];
}


public function build_topic_air() {
	$z=[];
	if(!$z=$this->CM->loadCache('airnow')){
	 	Log::error ("Could not load cache air");
	 	return [];
	 }
	$y = $this->format_airnow($z);
//U::echor($y,'air',STOP);
	return ['air' => $y];

}

// prepare data for the ight displlay:
// needs astro, today high/low wind
// must accomodate failed wapi or wgov

public function build_topic_fees() {
	$fees = LS::getFees();
	return ['fees'=>$fees];
}


public function build_topic_light() {
	$z = array(
	'Today'=>array(
		'sunrise'=>'',
		'sunset' => '',
		'wind' => '',
		'high' => '',
		'short' => '',
		'icon' => '',
		'uv' => '',

		),

	'Tonight'=>array(
		'moonrise'=>'',
		'moonset'=> '',
		'wind' => '',
		'low' => '',
		'short' => '',
		'icon' => '',
		'moonphase' => '',
		'moonillum' => '',

		),
	'update' => array(
		'ts' => time(),
		'source' => '',
		),
	);
	//Utilities::echor($z,'z init',STOP);

		// count how many periods captured.  If if it ends up 1, then
		// there is only a night component.  If it's 2, then there
		// is both day and night.
		// If only nighgt, then day info is left over from wapi, and is
		// for 29p, not jumbo rocks.

		$period_count = 0;

	if (1   //set to 0 to simulate wapi failure
		&& ($wapi = $this->CM->loadCache('wapi')['wapi'] )
		){

		// build light data from wapi (using black rock data)
		$period_count = 0;
		$today_done = false;
		foreach ($wapi['br']['forecast']['forecastday'] as $day) {
		// make sure it is not passed
			//echo "On {$day['date']} , ts {$day['date_epoch']} , date {$day['date']}" .BR;
			if (time() < $day['date_epoch'])   continue;
			//echo "passed. " . BR;

			++$period_count;
			$astro = $day['astro'];
			$high = round($day['day']['maxtemp_f']) . "&deg;F ("
				. round($day['day']['maxtemp_c']) . "&deg;C)";
			$low = round($day['day']['mintemp_f']) . "&deg;F ("
				. round($day['day']['mintemp_c']) . "&deg;C)";
			$wind = 'To ' . round($day['day']['maxwind_mph']) . 'mph' ;


			if (!$today_done) {
				$z['Today']['sunrise'] =  $this -> time_format( $astro['sunrise']);
				$z['Today']['sunset'] = $this -> time_format($astro['sunset']);
				$z['Today']['wind'] = $wind;
				$z['Today']['high'] = $high;
				$z['Today']['low'] = '';
				$z['Today']['short'] = $day['day']['condition']['text'];
				$z['Today']['icon'] = $day['day']['condition']['icon'];
				$z['Today']['uv'] = $day['day']['uv'] ;
				$z['Today']['period_count'] = $period_count;
				$z['Today']['endTimets'] = strtotime('6 PM');


				$z['Tonight']['moonrise'] =  $this -> time_format($astro['moonrise']);
				$z['Tonight']['moonset'] = $this -> time_format($astro['moonset']);
				$z['Tonight']['wind'] = $wind;
				$z['Tonight']['low'] = $low;
				$z['Tonight']['high'] = '';
				$z['Tonight']['icon'] = Defs::getMoonPic($astro['moon_phase']);
				$z['Tonight']['moonphase'] =  $astro['moon_phase'];
				$z['Tonight']['moonillum'] = $astro['moon_illumination'];
				$z['Tonight']['period_count'] = $period_count;

				$today_done = true;
			} else { #get tommorrow

				$high = round($day['day']['maxtemp_f']) . "&deg;F ("
					. round($day['day']['maxtemp_c']) . "&deg;C)";
				$low = round($day['day']['mintemp_f']) . "&deg;F ("
					. round($day['day']['mintemp_c']) . "&deg;C)";
				$wind = 'To ' . round($day['day']['maxwind_mph']) . 'mph' ;
				$z['Tomorrow']['sunrise'] =  $this -> time_format( $astro['sunrise']);
				$z['Tomorrow']['sunset'] = $this -> time_format($astro['sunset']);
				$z['Tomorrow']['wind'] = $wind;
				$z['Tomorrow']['high'] = $high;
				$z['Tomorrow']['short'] = $day['day']['condition']['text'];
				$z['Tomorrow']['icon'] = $day['day']['condition']['icon'];
				$z['Tomorrow']['uv'] = $day['day']['uv'] ;
				$z['Tomorrow']['period_count'] = $period_count;

				break;
			}
		}

		$z['update']['ts'] = $wapi['br']['current']['last_updated_epoch'];
		$z['update']['source'] = 'Forecast for Black Rock from weatherapi.com';
		$z['update']['period_count'] = $period_count;

		//Utilities::echor($z,'z init',STOP);

		//U::echor($z,'z from wapi');

	} else {
		die ("Can't load cache wapi");
	}
	// end wapi

	if (1   //change to 0 to simulate wgov failure
		&& ($wgov = $this->CM->loadCache('wgov')['wgov'] )
		&& (isset($wgov['jr']['properties']['updated'] ))
		){
		// update for wgov data
		$tomorrow_done = false;
//Utilities::echor($wgov,'wgov from cache');
		$wupdated = strtotime($wgov['jr']['properties']['updated']) ;
		$period_count = 0;

		foreach ($wgov['jr']['properties']['periods'] as $period) {
			$endTimets = strtotime($period['endTime']);
			if ($endTimets < time()){ #already ended
				//echo "Skipping period {$period['name']} ".BR;
				continue;
			}
			//if ($period['isDaytime']) continue; // test no night segment
			++$period_count;
		//	Utilities::echor($period,'period ');
			// found first period that has not ended yet

			if ($period['isDaytime'] && $period_count == 1) {
				$periodName = 'Today';
				$temperature = $period['temperature'];
				$tempc = round(($temperature -32 )* 5/9,0);

				$wind = $period['windSpeed'];
				$high =  "$temperature&deg;F ($tempc&deg;C)";
				$low = '';
				$short = $period['shortForecast'];
				$icon = $period['icon'];

			} elseif (!$period['isDaytime'])  {#got night time
				$periodName = 'Tonight';
				$temperature = $period['temperature'];
				$tempc = round(($temperature -32 )* 5/9,0);

				$high = '';
				$low = "$temperature &deg;F ($tempc &deg;C)";
				$wind = $period['windSpeed'];
				$icon = Defs::getMoonPic($astro['moon_phase']);
				$short = $period['shortForecast'];

			} #end night

			elseif ($period['isDaytime'] && $period_count > 1) { // tomorrow
				$periodName = 'Tomorrow';
				$temperature = $period['temperature'];
				$tempc = round(($temperature -32 )* 5/9,0);

				$low = '';
				$high = "$temperature&deg;F ($tempc&deg;C)";
				$wind = $period['windSpeed'];
				$icon = $period['icon'];
				$short = $period['shortForecast'];

				$tomorrow_done = true;


		}
				$z[$periodName]['wind'] = $wind;
				$z[$periodName]['low'] = $low;
				$z[$periodName]['high'] = $high;
				$z[$periodName]['icon'] = $icon;
				$z[$periodName]['short'] = $short;

				$z[$periodName]['period_count'] = $period_count;
				$z[$periodName]['endTimets'] = $endTimets;

			if ($tomorrow_done) break; // stop looking
		} #end foreach
		$z['update']['ts'] = $wupdated;
		$z['update']['source'] = 'Forecast for Jumbo Rocks from weather.gov';
		$this->sunset = $z['Today']['sunset'] ?? '';
	} #end wgov
 	//Utilities::echor($z,'light prepared');

	return ['light' => $z];
} #end function


public function build_topic_weather() {
/* delivers 'weather' array in same format
	using either wgov or wapi files.
	*/

	$fail = false;
	$update = 0;
	// set first term to 1 to force fail for testing.  Otherwise 0.
	if (0  || !$wgov = $this->CM->loadCache('wgov')['wgov']){
		$fail = true;
		$f = 'could not load wgov cache';
	}

	if (!$fail and !array_key_exists('jr',$wgov)){
		$fail = true;
		$f = 'No jr data in wgov';
	}
	if (!$fail
		&& !$update = strtotime($wgov['jr']['properties']['updateTime']) ?? 0
		){
		$fail = true;
		$f = 'Could not get update time for jr in wgov ';
	}
	if (!$fail &&  (time() - $update ) > 24*60*60) {
		$fail = true;
		$f = 'Wgov update over 24 hours old: ' . date('M d H:i',$update);
	}
	if (!$fail){

			$weather = $this->format_wgov($wgov);
			$weather['source'] = 'weather.gov';
			$w['weather'] = $weather;
			return $w;
		}
	else {Log::error ("Failed wgov ". $f);}

	// try wapi if wgov fails
	$fail = false;


	// set first term to 1 to force fail for testing.  Otherwise 0.
	if (0 || !$fail && !$wapi = $this->CM->loadCache('wapi')['wapi']) $fail=true;
	if (!$fail && !array_key_exists('hq',$wapi)) $fail = true;
	if (!$fail && !$update =  $wapi['hq']['current']['last_updated_epoch']) $fail = true;
	if (!$fail && (time() - $update) > 12*60*60) $fail = true;
	if (!$fail) {
			$weather = $this->format_wapi_like_wgov($wapi);
			$weather['source'] = 'weatherapi.com';
			$w['weather'] = $weather;
			return $w ;
	}

	echo ("All weather caches are stale "  .__FILE__ .' ['.__LINE__.']');
	return [];
}





public function build_topic_campgrounds() {
/*
*/
	$r['camps'] = $this->Camps->prepareDisplayCamps() ;
// admin cache contains status and notes for each cg
	//Utilities::echor($r, 'camps', STOP);
	return $r;
}

public function build_topic_npscal() {
	//builds calendar from the nsp cal
	$y = $this->CM->loadCache('npscal');

	//U::echor($y);
	$start = date('Y-m-d');
	foreach ($y['nps']['camps']['data'] as $event) {
		$ev['date'] = $event['datestart'];
		$ev['time'] = $event['times'][0]['timestart'];
		$ev['title'] = $event['title'];
		$ev['location'] = $event['location'];
		$evstart = new \DateTime($ev['date'] . ' ' . $ev['time']);
		$ev['dt'] = $evstart->format('U');
		if ($ev['dt'] < time()) continue; // it's over
		$evend = new \DateTime($event['dateend'] . ' ' . $event['times'][0]['timeend']);
		$ev['cancelled']='';
		$ev['type'] = $event['types']['0'];
		//$ev['note'] = $event['description'];
		$ev['note'] = '';
		$ev['reservation'] =
			$event['isfree'] == 'false'
			|| $event['isregresrequired'] == 'true'
			|| !empty($event['regresurl']);

		$enddt = $evend->format('U');
		$evsecs = $enddt - $ev['dt'];

		$ev['duration'] = U::humanSecs2($evsecs);

		$ev['suspended'] = false;
		$r[] = $ev;
	}
	$r = U::element_sort($r,'dt'); // sort by dt
	U::echor($r,'npscal prepared');
	$z['npscal']['events'] = $r;
	return $z;

}


public function build_topic_admin() {
	/* load date from admin cache, then reformat for display */

	if (!$y = $this->CM->loadCache('admin') ){
	 	Log::error ("Could not load cache admin");
	 	return [];
	 }
// 	Utilities::echor($y , 'loaded cache admin');
		//clean text for display (spec chars, nl2br) but don't change stored info.

		 	$t = $this->clean_text($y['pithy']);
			 $z['pithy'] = $t;

			$z['notices']['alert'] = $y['alertA'];


			$z['alert_alt'] = $y['alert_alt'] ?? '';

			$t = $this->clean_text($y['announcements']);
			$z['notices']['announcements'] = trim($t);
			//Utilities::echor ($z['notices'],'build topic general',STOP);

			$fire_level = $y['fire_level'];
			$z['fire']['level'] = $fire_level;
			$z['fire']['color'] = Defs::get_firecolor($fire_level);

			$z['version'] = file_get_contents(REPO_PATH . "/data/version") ?? 'Today';
			$z['target'] = date('l F j, Y');

			$z['advice'] = U::special($y['advice']);
			//$z['fixedAdvice'] = $y['fixedAdvice'];
			//$z['fixedAdvice'] = array_pop($z['advice']); // first item

			$z['rotate'] = $y['rotate'] ?? [];
			$z['rdelay'] = $y['rdelay'] ?? [];

// 	Utilities::echor($z,'topic general');
	return ['admin'=>$z];

}


public function build_topic_galerts () {

	/* must get uniform format for alerts to display right
	headline
	category
	event
	expires
	description
	action
two sources for alerts: wgovalerts and wapi cache already saved.
This function only save wgov alerts.  If both needed the other is
[ickec up in the display function.


	wgovalerts => [
		alerts,
	]

*/
	$y=[];

	// $src = 'wapi';
//
// 	 if (!$r = $this->loadCache('wapi',false) ) {
// 	 	Log::error ("Could not load cache wapi");
// 	 	return [];
// 	 }
//  //	Utilities::echor($r,'From wapi',STOP);
// 		$alerts = $r['jr']['alerts']['alert'];
// 		$x=[];
// 		foreach ($alerts as $alertno => $ad){
// 			$alert_exp = strtotime($ad['expires']);
// 			if ($alert_exp < time()) continue;
// 			$alert=[];
// 			$alert['headline'] = $ad['headline'];
// 			$alert['category'] = $ad['category'];
// 			$alert['event'] = $ad['event'];
// 			$alert['expires'] = $ad['expires'];
// 			$alert['description'] = $ad['desc'];
// 			$alert['instruction'] = $ad['instruction'];
// 			$alert['expire_ts'] = $alert_exp;
//				$alert['areaDesc'] = $ad['areaDesc']
//
// 			$x[] =$alert;
// 		}
// 		Log::info('Rebuilt galerts using wapi');
// 		$y[$src] = $x;


	$src = 'wgalerts';
	$locs = ['jr'];

	$wgalerts = $this->CM->loadCache('wgalerts');
// Utilities::echor($wgalerts,'get wgalerts',0);
	foreach ($locs as $loc) {

		$alerts = $wgalerts[$loc]['features'];
// 	U::echor($alerts,'alerts',0);
		$x = [];
		foreach ($alerts as $item){
			$ad = $item['properties'];
//	echo $ad['headline'] . BR;
			$alert_exp = strtotime($ad['expires']);
			if ($alert_exp < time()) continue;
			$alert=[];

			$alert['headline'] = $ad['parameters']['NWSheadline'][0];
//	U::echor ( $alert['headline'],'params') . BR;
			$alert['category'] = $ad['category'];
			$alert['event'] = $ad['event'];
			$alert['expires'] = $ad['expires'];
			$alert['ends'] = $ad['ends'];
			$alert['ends-short'] = ($alert['ends']) ? date('M d g:i a', strtotime($ad['ends'])) : '';
			$alert['description'] = $ad['description'];
			$alert['instruction'] = $ad['instruction'];
			$alert['expire_ts'] = $alert_exp;
			$alert['areaDesc'] = $ad['areaDesc'];

			$x[] =$alert;

		}
//	U::echor($x,'x',0);
		$y[$loc] = $x ;
	}
		//Log::info('rebuilt galerts using wgovalerts');

// Utilities::echor($y,'from external  alerts', STOP);
	return ['galerts'=>$y];
}







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
			$aqi_scale = Defs::aq_scale($aqi);
			$aqi_color = Defs::scale_color($aqi_scale);

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

	foreach ($r['airnow'] as $loc => $d){
			// d may have multiple segments with diffeent tests (O3, PM10, etc)
			$aqi=1000;
			$y['pm10'] = 'n/a';
			$y['o3'] = 'n/a';
			foreach ($d as $aqtest){
				// get the min aqi value, adn set vars.  Reports separate aqi for each var
				$aqi = min($aqi,$aqtest['AQI']) ;
				if (isset ($aqtest['PM10'])) $y['pm10'] = $aqtest['PM10'] ;
				if (isset ($aqtest['O3'])) $y['o3'] = $aqtest['O3'] ;

			}
			$y['aqi'] = $aqi;
			$y['observed_dt'] = strtotime($aqtest['DateObserved'] . ' ' . $aqtest['HourObserved'] . ':00') ;
			$y['reporting'] = $aqtest['ReportingArea'];
			$y['aqi_scale']  = ($aqi)? Defs::aq_scale($aqi) : '';
			$y['aqi_color'] = ($aqi) ? Defs::scale_color($y['aqi_scale']) : '';
			$y['airwarn'] = Defs::getAirWarn($y['aqi_scale']);

			$x[$loc] = $y;
		}



	return $x;

 }



public function format_wapi ($r) {

	$x = [];
	$x['update'] = time();// will end up with $y[$src] = $x;
//Utilities::echor($r,'R',STOP);

	foreach ($r as $loc => $ldata){
		 $forecast = $ldata['forecast']['forecastday'];
		 // there are forecasts for 3 days
		for ($i=0;$i<5;++$i){
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
				'winddir' => '',
				'avghumidity' => $daily['day']['avghumidity'],
				'maxwind' => round($daily['day']['maxwind_mph']),
				'maxwindM' => round($daily['day']['maxwind_kph']),

				'short' => $daily['day']['condition']['text'],
				'rain' => $daily['day']['daily_chance_of_rain'],
				'visibility' => $daily['day']['avgvis_miles'],
				'uv' => $daily['day']['uv'],
				'icon' => $daily['day']['condition']['icon'],

				);
		} #end for day

		$x['forecast'] = $w;

	// add airquality current
		 $current_aq = $r[$loc]['current']['air_quality'];
		 $current_aq['updated_ts'] = $r[$loc]['current']['last_updated_epoch'];

		 $x['aq'][$loc] =  $current_aq ;
	} #end location

	// add astro and alerts for jr today

	$astro = $r['br']['forecast']['forecastday']['0']['astro'];
	$dayuv = $r['br']['forecast']['forecastday']['0']['day']['uv'];
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
//Utilities::echor($x,'x',STOP);
	return $x;
}


public function format_wapi_like_wgov ($r) {

	$x = [];
	$x['update'] = time();// will end up with $y[$src] = $x;
//Utilities::echor($r,'R',STOP);

	foreach ($r as $loc => $ldata){
		 $forecast = $ldata['forecast']['forecastday'];
		 // there are forecasts for 3 days
		 $day = 0;
		 $lastday = '';
		foreach ($forecast as $daily){
			$startts = $daily['date_epoch']; // gmt
			if ($startts < strtotime('yesterday')) continue; // yesterday
			$daytext = date('l, F j', $startts + 8*60*60); // from gmt to pst
		//echo "Starting $loc $daytext " . $daily['date'] .  BR;
			$nightts = strtotime($daytext . ' 6:00pm');
			/*
			$w[$loc][$day]['Day'] = array(
				'dayts' => $daily['date_epoch'],
				'daytext' => date('l, F j', $startts),
				'High' => round($daily['day']['maxtemp_f']) ,
				'HighC' => round($daily['day']['maxtemp_c']) ,
				'Low' => round($daily['day']['mintemp_f']) ?? 'n/a' ,
				'LowC' => round($daily['day']['mintemp_c']) ?? 'n/a' ,
				'winddir' => '',
				'avghumidity' => $daily['day']['avghumidity'],
				'maxwind' => round($daily['day']['maxwind_mph']),
				'maxwindM' => round($daily['day']['maxwind_kph']),

				'short' => $daily['day']['condition']['text'],
				'rain' => $daily['day']['daily_chance_of_rain'],
				'visibility' => $daily['day']['avgvis_miles'],
				'uv' => $daily['day']['uv'],
				'icon' => $daily['day']['condition']['icon'],

				);
			*/

			$High = round($daily['day']['maxtemp_f']) ;
				$HighC = round($daily['day']['maxtemp_c']) ;
				$Low = round($daily['day']['mintemp_f']) ?? 'n/a' ;
				$LowC = round($daily['day']['mintemp_c']) ?? 'n/a' ;
				$maxwind = round($daily['day']['maxwind_mph']);
				$maxwindM = round($daily['day']['maxwind_kph']);

				$w[$loc][$day]['Day'] = array(
					'short' => $daily['day']['condition']['text'],
					'long' => '',
					'icon' => $daily['day']['condition']['icon'],
					'startts' => $startts,
					'daytext' => $daytext,
					'highlow' => '',
					'wind' => "$maxwind mph ($maxwindM kph)",
					'isDaytime' => true,
					'temp' => "$High&deg;F ($HighC&deg;C)",

				);

				$w[$loc][$day]['Night'] = array(
					'short' => '',
					'long' => '',
					'icon' => '',
					'startts' => $nightts,
					'daytext' => $daytext,
					'highlow' => '',
					'wind' => '',
					'isDaytime' => false,
					'temp' => "$Low&deg;F ($LowC&deg;C)",

				);


			++$day;
			//if ($day > 3) break;
			}
		} #end loc

	$x['forecast'] = $w;
//U::echor($x,'wapi as wgov');
	return $x;
}


private function format_alerts($alert){
	if (empty($alert)) return '';
	if (empty($alert['title']) or ($alert['expires'] < time() )) return '';
	$expire_date = date('M d g:i a',$alert['expires']);

	$t= "<div class='alertblock'>";
	$t .= "<div class='red inlineblock'><b>Alert: {$alert['title']}</b> </div> <br />";
	if ($alert['text']){
		$t .= "<div class='inline-block indent'>"
		. Utilities::special($alert['text'])
		. "</div>";
	}
	$t .= "<div class='inlineblock right width100' style='font-weight:normal;'><small>Expires: $expire_date</small></div>";
	$t .= "</div>";

//Utilities::echot($t,'formatted alert',STOP);
	return $t;
}

public function format_wgov ($wgov) {

	$x=[];
	//Utilities::echor ($wgov, 'wgov into format');

	if (! isset($wgov['jr']['properties']['updated'] )){
		echo "no jr properties";
		return $x;
	}
	$wupdated = strtotime($wgov['jr']['properties']['updated']) ;
	$x['update'] = $wupdated;

	foreach ($wgov as $loc => $ldata){	//uses weather.gov api directly
	//Utilities::echor($ldata, "ldata for $loc");

		if (! $ldata){continue;}

		//$x['update'] = strtotime($ldata['properties']['updated'] ?? 0);

		$periods = $ldata['properties']['periods'] ?? '';

		$day = 0;
		$lastday = '';
	foreach ($periods as $perdata){ // period array]	d
			// two periods per day, for day and night
			// put into one array
// Utilities::echor($p,'period',NOSTOP);
	// set day (key) to datestamp for day, not hours
			$start = $perdata['startTime'];
			$end = $perdata['endTime'];
			if (strtotime($end) < time()) continue; //expired

			$startts = strtotime($start);
			$daytext = date('l, M d',$startts);

			if ($day > 5) break;

			$highlow = $perdata['isDaytime']? 'High':'Low';
			$daynight= $perdata['isDaytime']? 'Day':'Night';
			$tempc = round(($perdata['temperature'] -32 )* 5/9,0);
			$temp = $perdata['temperature'] . "&deg;F (" .$tempc . "&deg;C)";

			$wdata['short'] = $perdata['shortForecast'];
			$wdata['long'] = $perdata['detailedForecast'];
			$wdata['icon'] = $perdata['icon'];
			$wdata['startts'] = $startts;
			$wdata['daytext'] = $daytext;
			$wdata['highlow'] = $highlow . "&nbsp;" .
				$perdata['temperature'] . "&deg;F (" .$tempc . "&deg;C)" ;
			$wdata['temp'] = $temp;
			$wdata['wind'] = $perdata['windSpeed'];
			$wdata['isDaytime'] = $perdata['isDaytime'];

			$x['forecast'][$loc][$day][$daynight] = $wdata;

		if ($daynight == 'Night' && $daytext != $lastday){
				++$day;
				$lastday = $daytext;
			}
		} #end foreach period

	} #end foreach location

//Utilities::echor($x,"formatted wgov");
	return $x;
}

public function getAdminParams(){
	// retrieve rpages and rdelay from admin
	$admin = $this->CM->loadCache('admin');
	$rparams = [
		'rotate' => $admin['rotate'],
		'rdelay' => $admin['rdelay'],
		'pithy' => $admin['pithy'],
	];
	return $rparams;
}

public function getSunset(){
		$wapi = $this->CM->loadCache('wapi');
		$sunset = $wapi['wapi']['hq']['forecast']['forecastday'][0]['astro']['sunset'];
		return $this->time_format($sunset);
	}

// -----------   UTILITY FUNCTIONS -------------

private function time_format($time) {
	// remove leading 0

	if (substr($time,0,1) == '0'){
		$time = substr ($time, 1);
	}
	$time=str_replace(' AM','&nbsp;am',$time);
	$time=str_replace(' PM','&nbsp;pm',$time);

	return $time;
}



public  function uv_data($uv) {
	// takes numeric uv, returns array of uv, name, warning
		$uvscale =  Defs::uv_scale($uv);
		$uv = array(
			'uv' => $uv,
			'uvscale' => $uvscale,
			'uvwarn' => Defs::uv_warn($uvscale),
			'uvcolor' => Defs::get_color($uvscale),
		);
			return ($uv);
}

private function fire_data($fire_level) {
	$fire = array (
		'level' => $fire_level,
		'color' => Defs::get_firecolor($fire_level),
		);

	return $fire;
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
