<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\InitializeCache;
use DigitalMx\jotr\CacheSettings as CS;
use DigitalMx\jotr\Api;
use DigitalMx\jotr\LocationSettings as LS;

use GuzzleHttp\Client as Client;
use GuzzleHttp\Exception;
// use GuzzleHttp\Pool;
use GuzzleHttp\Psr7 as Psr7;
use GuzzleHttp\Psr7\Response as Response;
use GuzzleHttp\Psr7\Request as Request;

########  self::$cacheFilesS #############
/*
	Caches contain all the data retrievedd or stored
	from various sources, both internal and external.
	There is one cache for each source, so minimal re-formatting
	of the raw source.  Caches are all json arrays.

	Caches need to be refreshed periodically.  The refresh
	time for each cache is stored in CacheSetting::$cacheTimes,
		Refresh time is 0 for caches that are manually updated,
	like admin.  So refresh =
	$limit && (time() - $limit > 0)

	Tried two alternatives:
	Refresh all caches periodically with cron (refresh_caches()).
	Refresh each cache on loading it (refreshCache($cache)).

	Former is bertter because it does the refresh in the background,
	so is invisible to the user.  Otherwise user can get 'hung'
	becausee of possible delays or issues with the refresh.

	Caches are all organized as arrays:
	src => [
		loc1 => [
			data
			]
		loc2 ...
	]

	So combine arrays into one to work with templtate
	$y = array_merge (get cache 1, get cache2 ...)
	render (plate, ['data'=>$y]);
	will get each source as a var in the template.

	or

	$src = loadCache($src)
	foreach ($src as $loc)
	  $formatted[$src] => xxx
	}
	render (plate, ['data' =>$formatted ]
	*/







class CacheManager {



	private $reccamps = ['jr','br','ic','ry','cw','sp'];
	private $wlocs = ['hq','jr','br','cw'];
	private $airlocs = ['hq','jr','br','cw'];
	private $currentlocs = ['lhrs'];

public function __construct() {


}

public function writeCache(string $section,array $z) {
	if (empty($z)){
	Log::error("Writing empty array to $section") ;
	}
	if($this->file_put_contents_locking (CS::getCacheFile($section),json_encode($z))){
		//Log::info("Writing cache $section");
	} else {
		Log::error("Cannot write cache $section due to lock");
		//die("Error: cannot write $section due to lock file");
	}
}



public function loadCache ($cache) {
	// normally reports  out of date cacghe and rebuilds it.
	// prevent refresh check by setting refresh - false.
	// This is need to prevent endless loop if rebuild includes another load.

#echo "loading cache $section" . BR;


	// check validity
		if (!$cachefile = CS::getCacheFile($cache) ) die ("Undefined cache file for $cache");
		if  (!file_exists ($cachefile )) {
			// Log::info("rebuilding non-existent cache $section.");
// 			$this->refreshCache($section,true);
				echo ("Requested cache file $cachefile does not exist");
				Log::notice("attempt to load non-existent cache $cache");
				return [];
		}

		$age = $this->ageCache($cache);
		$limit = CacheSettings::getCacheLimit($cache);

		if ($limit>0 && ( $age > 2*$limit)){
			Log::notice("Loading $cache is stale. $age minutes");
		}
		$y=[];
		if (!$y = json_decode ($this->file_get_contents_locking($cachefile), true)) {
			Log::error("Failed to json decode cache $cache.  ");
			return [];
		}

		if (empty($y)) {
			Log::error("Failed to load cache $cache.  Returning empty.");
			return [];
		}
		//Utilities::echor($y,$section,NOSTOP) . BR;
		//$this->$section = $y;
		return $y;
}

public function ageCache($cache,$mt=false) {
	//returns age in minutes since mod
	if (!file_exists(CS::getCacheFile($cache))) return 10000;
	$filetime = filemtime (CS::getCacheFile($cache));
	$age = round((time() - $filetime)/60); // in minutes
	if (!$mt) return $age;
	return [$age,$filetime];

}

private function expiredCache($cache) {
	//returns true if cache limit = -1 or age > imit or no file
	// everything in minutes, not seconds
	$limit = CacheSettings::getCacheLimit($cache);
	if ($limit == 0) return false;
	if ($limit < 0) return true;
	$cachefile = CS::getCacheFile($cache);
	if (! file_exists($cachefile)) return true;
	$age = $this->ageCache($cache);
	return ($age>$limit);
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




public function rebuild_cache_airnow() {
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

	$src = 'airnow';
	$ok=true;
	$locs = $this->airlocs;

	$api = new Api($src);
	$relurl = '';

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$query = [
		'format'=>'application/json',
		'latitude' => $lat,
		'longitude' => $lon,
		'distance' => 75,
		'API_KEY' => CS::getKey($src),
			];

		$loginfo = "$src:$loc";

		if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			$resp[$src][$loc] = $r;
			$this->mergeCache($src,$resp);
		} else {
			Log::error("Failed to get $loginfo");
			continue;
		}
	} # next loc
	return true;
}

public function rebuild_cache_wgalerts() {
/*
>>>>>>> api

https://api.weather.gov/alerts/active/zone/{zoneId}

zones:
Lost Horse (west JtNP, BR) zoneid=CAZ560  fire CAZ230
cw
jr CAZ560, fire CAZ230
kv CAZ560, fire CAZ230
JTNP East CAZ561
JTNP CAZ230
Morongo basin CAZ525
Coachella valley CAZ061
PSP CAZ261
Yucca CAZ228
Salton CAZ563
Siskiyou CAZ285
PDX ORZ006


 atom	CAZ561	Joshua Tree NP East
 atom	CAZ560	Joshua Tree NP West
 atom	CAZ230	Joshua Tree National Park

*/
	$locs = ['jr','shasta'];
	//getting shasta to improve likelyhood of getting something.
	$src = 'wgalerts';
#	$zone = 'CAZ560'; #joshua tree np
#	$zone = 'ORZ006'; #pdx
#	$zone = 'CAZ082'; #shasta
#	$zone = 'COZ040'; #denver
	$query = [];
	$ok=true;
	 $api = new Api($src);

	foreach ($locs as $loc){

		if (!$zone = LS::getZone($loc)){
			Log::error("WGalert error: no zone for $loc.");
			continue;
		}
		$relurl = $zone;
	 	$loginfo = "$src:$zone";

 		if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			$resp[$loc] = $r;
			// test: if ($loc == 'jr') $ok=false;
			//$ok doesn't change
		} else {
			Log::notice("Failed to get $loginfo",$r);
			$ok = false;
			continue;
		}
	}
	if ($ok){
		$this->writeCache($src,$resp);
		return true;
	} else {
		return false;
	//Log::info("Saved updated cache $src");
	}
}

public function rebuild_cache_wgov() {
/*
https://api.weather.gov/gridpoints/{office}/{grid X},{grid Y}/forecast

https://api.weather.gov/gridpoints/PSR/13,102/forecast

z
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


	$src = 'wgov';
	$locs = $this->wlocs;
	#$locs = ['br'];


 $api = new Api($src);
 $query = [];
	$ok = true;

	foreach ($locs as $loc) {
		$loginfo = "$src:$loc";

		$relurl =  LS::getGridpoints($loc) . '/forecast' ; #./forecast
		if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			$resp[$src][$loc] = $r;
			// test: if ($loc == 'jr') $ok=false;
			//$ok doesn't change
		} else {
			Log::notice("Failed to get wgov $loginfo",[$r]);
			$ok = false;
			continue;
		}

	} # next loc

	// using merge instead of write because some sites may have failed.
	if (empty($resp)){
		Log::notice("No results for wgov. Not updating cache.");
		$ok=false;
	} else {
		$this->mergeCache($src,$resp);
		//Log::info("Merged data into cache wgov");

	}

	//$this->wgovupdate = strtotime($x['jr']['properties']['updated']);
	return $ok;
}

public function mergeCache($cache,$merge){
// merges data into cache, unless data is empty
		$x = $this->loadCache($cache);
		// returns [] if no cache file

		if (! empty ($merge)){ #if empty you're done
			$y = array_merge($x,$merge);
// 		Utilities::echor ($y,'merged cache');
			$this->writeCache($cache,$y);
		}
	}



public function rebuild_cache_current () {
	/* latest data from LHRS
	curl -X GET "https://api.weather.gov/stations/LTHC1/observations/latest" -H "accept: application/geo+json"
	*/
	$src = 'current';
	$ok=true;
	$locs = $this->currentlocs;  //stations
	$query = [];
	$api = new Api($src);
	foreach ($locs as $loc) {
		$station = CS::getStation($loc);
		$relurl = "$station/observations/latest";

		$loginfo = "$src-$loc";

		if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			$resp[$src][$loc] = $r;
			// test: if ($loc == 'jr') $ok=false;
			//$ok doesn't change
		} else {
			Log::notice("Failed to get $loginfo",$r);
			$ok = false;
			continue;
		}
		//Utilities::echor($aresp,'current aresp');
		if (is_null($resp[$src][$loc]['properties']['temperature']['value'] )) {
				Log::warning ("Received null temp for $loginfo");
		}

	} #next loc
	//Log::info("Saved updated cache $src");
	if ($ok){
		$this->writeCache($src,$resp);
		return true;
	}else{
		return false;
	}
}

public function rebuild_cache_airowm() {
	$x=[];
	$src = 'airowm';
	$api = new Api($src);
	$ok = true;
	$relurl = '';
	$locs = $this->airlocs;

	//for 1call 3.0
	// https://api.openweathermap.org/data/3.0/onecall?lat={lat}&lon={lon}&exclude={part}&appid={API key}
	// for original
	//$url = "http://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid=" . CS::getKey('openweathermap');
	//

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);

		$loginfo = "$src:$loc";
				$query = [
		'exclude'=>'minutely,alerts',
		'lat' => $lat,
		'lon' => $lon,
		'appid' => CS::getKey($src),
	];
		if ($r=$api->apiRequest($loginfo, $relurl,$query)){
				$resp[$src][$loc] = $r;
				// test: if ($loc == 'jr') $ok=false;
				//$ok doesn't change
		} else {
				Log::notice("Failed to get $loginfo",$r);
				$ok = false;
				continue;
		}

	} # next loc

	if ($ok){
		$this->writeCache($src,$resp);
	//Log::info("Saved updated cache $src");
		return true;
	} else {
		return false;
	}
}

public function rebuild_cache_npscal() {
	// retrieves 5 days of calendar events from nps.gov site
	$src = 'nps';
	$ok=true;
	$headers = [];
	$api = new Api($src);
	$dt = new \DateTime();
	$dateStart = $dt->format('Y-m-d');
	$loc = 'camps';
	$dt->modify('+7 days');
	$dateEnd = $dt->format('Y-m-d');
	$query = ['parkCode'=>'jotr','dateStart'=>$dateStart,'dateEnd'=>$dateEnd,'api_key' => CS::getKey($src)];
	// to exapnd all repeats into sep records, add 'expandRecurring'=>'true'

	$loginfo = "$src-$loc";
	$relurl = '';
	if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			$resp[$src][$loc] = $r;
			// test: if ($loc == 'jr') $ok=false;
			//$ok doesn't change
		} else {
			Log::notice("Failed to get $loginfo",$r);
			$ok = false;

		}
		//U::echor($resp,'cal',STOP);
	if ($ok){
		// save the raw download.  Can't say why
		$this->writeCache('npscalraw',$resp);

		$npscal = $this->format_nps($resp);
		$this->writeCache('npscal',$npscal);

		return true;
	} else {
		return false;
	}
	//Log::info("Saved updated cache $src");


}

private function format_nps($rawnps) {
	//builds calendar from the nsp cal


	/* need:
	title
	type
	duration
	location
	time
	date
	end
	day0, day1 ,... day6 (sun..sat)
	suspended
	canceldate
	note
	reservation
	days
	dt
	*/
	/* recording this as a repeating event is difficult

		Simpler solution.  Each record contains the specific date in the
		next few days that the event occurs.  So build a new field for dates
		(and one for times too).  When expanding out to individual records,
		the filter script will detect this and produce correct events.

		These records all have NPS id, so cancel/suspend/notes can be tied to
		that id, so they don't get lost when data is refreshed.

	*/
	// needed to translate NPS days to day0..day6
	$daylist = array('SU','MO','TU','WE','TH','FR','SA');
	foreach ($rawnps['nps']['camps']['data'] as $event) {
		$ev = [];

		$ev['title'] = $event['title'];
		$ev['location'] = $event['location'];
		$ev['npsid'] = $event['id'];
		$ev['type'] = $event['types']['0'];
		$ev['note'] = ''; // $event['description'];
		$ev['dayset'] = '';

		//get duration: end - start
		$startdt = strtotime($event['times'][0]['timestart']);
		$enddt = strtotime($event['times'][0]['timeend']);
		$ev['duration'] = U::humanSecs2($enddt - $startdt);
		$ev['regresurl'] = $event['regresurl'];
		// res required?
		$ev['reservation'] = (
			$event['isregresrequired'] == 'true'
			|| $event['isfree'] == 'false'
			) ? 1 : 0;

		if ($event['isrecurring'] == 'false') { //recurring record data
			$ev['date']= $event['date'];  //next occurence.  not useful ??
		}
		if (1) { // code to parse the recurring rule in the nps cal
			$ev['date'] = $event['recurrencedatestart'];
			$ev['end'] = $event['recurrencedateend']??'';
			$rec = $event['recurrencerule'];
			preg_match('/BYDAY=([\w\,]*);/',$rec,$m);
			$byday = $m[1];
			$ev['dayset']=[];
		//echo $ev['title'] . ' '  .$byday . BR;
			for ($i=0;$i<=6;++$i){
				if (strpos($byday,$daylist[$i]) !== false) {
					$ev['dayset'][$i] ='on';
				}
			}
		}

		//date and time can have multiples in one record
		// will save here and parse out in filter
		foreach ($event['times'] as $stimes)
			 $ev['times'][]=$stimes['timestart'];
		$ev['dates'] = $event['dates'];

		$ev['date'] = $ev['dates'][0];
		$ev['time'] = $ev['times'][0];
		$ev['dt'] = 0;

		$ev['canceldate']='';
		$ev['suspended'] = false;


	// get days

	//U::echor($ev, 'event');

// use id as key so I can replace/add/remove from merged cal
		$r['npscal'][] = $ev;

	}
	return $r;

}
private function rebuild_cache_calendar () {
	$x = $this->loadCache('calendar');
	if (!isset ($x['events'])){$x=['events'=>$x];}
	$x['events'] = Calendar::filter_events($x['events'],0);
	$this->writeCache('calendar',$x);
	//Log::info('rebuilt calendar cache');
	return true;
}

public function rebuild_cache_airq() {
echo ("airq not working" . BR); return false;
	$x=[];
	$ok = true;
	$src = 'airq';
	$locs = $this->airlocs;
	$header = [
			"X-RapidAPI-Host"=>"air-quality.p.rapidapi.com",
			"X-RapidAPI-Key" => '3265344ed7msha201cc19c90311ap10b167jsn4cb2a9e0710e',
				];
		$api = new Api($src,$header);
	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$query = ['lon'=>$lon,'lat'=>$lat];
		$relurl = '';
		$loginfo = "$src:$loc";
		if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			$resp[$src][$loc] = $r;
			// test: if ($loc == 'jr') $ok=false;
			//$ok doesn't change
		} else {
			Log::notice("Failed to get $loginfo",$r);
			$ok = false;
			continue;
		}

	} # next loc

	if ($ok){
		$this->writeCache($src,$resp);
	//Log::info("Saved updated cache $src");
		return true;
	} else {
		return false;
	}
}

public function rebuild_ref_request() {
	$this->writeCache('refRequest',['refreshed']);
	return true;
}
public function rebuild_cache_CampsRec (){
	$x=[];
	$src = 'ridb';
	$ok=true;
	$locs = $this->reccamps;
	$apikey = CS::getKey('ridb');
	$d = new \DateTime('today',new \DateTimeZone('UTC'));
	$dateiso = $d->format('Y-m-d\TH:i:s\Z');;
	$header = [];

	$api = new Api($src);
	//https://ridb.recreation.gov/api/availability/camping/272300?apikey=3c4e8e8a-10d0-4512-8459-c79fe9d9a8b1
	foreach ($locs as $loc) {
		$facilityID = LS::getFacility($loc);

		$relurl = 'camping/' . $facilityID;

		$loginfo = "$src:$loc";
		$query = ['apikey'=>$apikey];

		if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			// go on
			// $rec = [campsites=>siteid=>'availabilities'=>'2023-01-14T00:00:00Z' = 'Rewsereved|Not Avaialbe|Available
//U::echor($r,$loginfo,STOP);
		} else {
			Log::notice("Failed to get $loginfo",$r);
			continue;
		}

		if (!$cgavail = $this->parseRecCamps($r)){
			Log::notice("Camps $loginfo has no campsites.",$r);
			//don't change avaibility for this campground

		} elseif (isset( $cgavail[$dateiso] )){
			$availability[$loc]  = $cgavail[$dateiso] ;

		} else {
			Log::error ("Camp $loc has no date entry for $dateiso");

			// or no change??
		}

	} # next loc
	//U::echor ($availability,'availability',STOP);
	$camps = $this->loadCache('camps');

	foreach ($availability as $cg=>$open){
		$camps['cgs'][$cg]['open'] = $open;
		$camps['cgs'][$cg]['asof'] = time();
	}
	if ($ok){
		$camps['rec.gov_update'] = date('M d g:i a');
		$this->writeCache('camps',$camps);
		return true;
	} else {
		return false;
	}
}

public function rebuild_cache_tours (){
	Log::notice("attempt to rebuild tours not functional");
	echo "Tours not functional" . BR;
	return false;
	$resp=[];
	$ok = true;
	$src = 'ridb';
	$locs = ['krtour'];#$this->reccamps;
	$apikey = CS::getKey('ridb');
	$d = new \DateTime('today',new \DateTimeZone('UTC'));
	$dateiso = $d->format('Y-m-d\TH:i:s\Z');;
	$header =  [];

	$api = new Api($src);

	echo "Startin tour update" . BR;
	foreach ($locs as $loc) {
		$loginfo = "$src $loc";
		$facilityID = LS::getFacility($loc);
		$relurl = '/tours/' . $facilityID;
		$query = ['apikey'=>$apikey];

		if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			$resp[$src][$loc] = $r;
			// test: if ($loc == 'jr') $ok=false;
			//$ok doesn't change
		} else {
			Log::notice("Failed to get $loginfo",$r);
			$ok = false;
			continue;
		}

		$cgavail = $this->parseRecCamps($r);
		//U::echor($cgavail,'cgavail');

		if (isset( $cgavail[$dateiso] )){
			$availability[$loc]  = $cgavail[$dateiso] ;
		} else {
			$availability[$loc] = 0;
		}

	} # next loc
	U::echor ($availability,'availability',STOP);
	if ($ok){
		$this->writeCache('tours',$availability);
		return true;
	//Log::info("Tours updated from rec.gov");
	}else{
		return false;
	}
}

public function rebuild_cache_wapi( ) {
	$x=[];
	$ok = true;
	$src = 'wapi';
	$locs = $this->wlocs;
	$api = new Api($src);
	$query= [
			'key' => CS::getKey('weatherapi'),
			'days'=>'3',
			'aqi' => 'yes',
			'alerts' => 'yes',
			];
	$relurl = '';

	foreach ($locs as $loc) {
		$loginfo = "$src:$loc";
		$query['q'] = LS::getCoords($loc);


		$url = 'http://api.weatherapi.com/v1/forecast.json?key=' . CS::getKey('weatherapi') . '&q='. LS::getCoords($loc) . '&days=3&aqi=yes&alerts=yes';
		// echo "url: $url". BRNL;
// 		U::echor($query,'query');

		$retries=1;


		if ($r=$api->apiRequest($loginfo, $relurl,$query,$retries)){
			$resp[$src][$loc] = $r;
			// test: if ($loc == 'jr') $ok=false;
			//$ok doesn't change
		} else {
			Log::warning("Failed to get a response for $loginfo",$r);

			continue;
		}


	} # next loc
	if ($ok){
		$this->writeCache($src,$resp);
		//Log::info("Saved updated cache $src");
		return true;
	} else {
		return false;
	}

}




public function rebuild_cache_props() {
	$locs=['br','cw','jr','kv','lhrs','pdx','shasta'];
	$x=[];
	$ok=true;
	$x['update'] = time();
	$src = 'props';
	$api = new Api($src);
	$query=[];
	foreach ($locs as $loc){
		$loginfo = "$src: $loc";


		$relurl = '/points/' . LS::getCoords($loc);
		//$url = "https://api.weather.gov/points/$lat,$lon";
					//(https://api.weather.gov/points/{lat},{lon}).


	if ($r=$api->apiRequest($loginfo, $relurl,$query)){
			$resp[$src][$loc] = $r;
			// test: if ($loc == 'jr') $ok=false;
			//$ok doesn't change
		} else {
			Log::notice("Failed to get $loginfo",$r);
			$ok = false;
			continue;
		}

		$x[$src][$loc] = $r;
	}
	if ($ok){
		$this->writeCache($src,$x);
	//Log::info("Retrieved properities");
		return true;
	} else {
	return $ok;
	}
}

public function refreshAllCaches($force=false) {
	$lmessage = "Starting all cache refresh cycle";
	Log::info ($lmessage );
	echo $lmessage . BR.BR;

/* refreshes all the external caches, if they are due

*/
	$clist = CS::getCacheList();
	echo sizeof($clist) . ' caches' . BR;
	foreach ($clist as $cache){
		$this->refreshCache($cache,$force) ;
	}

	#	$this -> rebuild_properties('jr');
	Log::info ("Completed cache refresh cycle");

}


public function refreshCache($cache,$force=false) {
	// refresh individual cache on demand.
	/* back and forth over refresh all via cron
	vs refrewsh on deman when loaded.  Problem with
	doing it on demand is that it may tie up script
	for a while due to sleep cycles for failed curl
	attempts.  Better to run refresgh as a background
	job by cron.  So use 'refresh_caches'

	Force = 0 -> run normally
	Force = 1 -> run refresh regardless of ot

	If refresh fails..
	at start, capture file mtime
	if failes, reset mtme to starting value so it ages out asap.


	*/


	if (!$force && !$this->expiredCache($cache)){
		echo "$cache Skipped: not stale" . BR; return true;
		}
	list($age,$mt) = $this->ageCache($cache,true);
	// save mtime to resdtore if update fails

	$rf = false;
	//Log::info("Starting refresh on $cache");
		switch ($cache) {
			case 'admin':
				$rf = true;
				break;

			case 'airnow':

				$rf = $this->rebuild_cache_airnow();
				break;

			case 'airowm':
				$rf = $this->rebuild_cache_airowm();
				break;

			case 'airq':
				$rf = $this->rebuild_cache_airq();
				break;

			case 'calendar':
				$rf = $this->rebuild_cache_calendar(); #filter out old stuff
				break;

			case 'camps':
				// update open from rec.go
				$rf = $this->rebuild_cache_CampsRec();
				break;

			case 'current':
				$rf = $this->rebuild_cache_current();
				break;

			case 'props':
				$rf = $this->rebuild_cache_props();
				break;
			case 'tours':
				$rf = $this->rebuild_cache_tours();
				break;
			case 'wgalerts':
				$rf = $this->rebuild_cache_wgalerts();
				break;


			case 'wapi':
				$rf = $this->rebuild_cache_wapi();
				break;

			case 'wgov':
				$rf = $this->rebuild_cache_wgov();
				break;

			case 'npscal':
				$rf = $this->rebuild_cache_npscal();
				break;

			case 'refRequest':
				$rf = $this->rebuild_ref_request();
				break;

			default:
				Log::error ("Attempt to refresh unknown cache $cache");
				return false;
		}

		if ($rf){
			Log::info ("$cache Refreshed. (age: $age) ");
			echo " Refreshed $cache." . BR;
			return true;
		} else {
			Log::info("$cache refresh failed.");
			echo "$cache not completely refreshed. Mtime reset." . BR;
			// restore mtime
			$this->setMtime($cache,$mt);

			return false;
		}

}

private function initializeCache($cache) {
	switch ($cache) {
		case 'admin':
			$this->writeCache($cache,InitializeCache::$admin);
			break;
		case 'calendar':
			$this->writeCache($cache,InitializeCache::$calendar);
			break;
		case 'camps':
			$this->writeCache($cache,InitializeCache::$camps);
			break;
		case 'nspcal':
			$this->writeCache($cache,[]);
			break;

		default:
			// do nothing

	}
	return true;
}


public function format_galerts(){
	// retrieves alerts from wgalerts and wapi and formats
	// for display in admin screen

	$galerts = $this->loadCache('wgalerts');
	$x = [];
	foreach ($galerts['features'] as $galert){
#	Utilities::echor($galert,'galert',STOP);

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


			$x['wgalerts'][] =$alert;

		}
		$wapialerts = $this->loadCache('wapi')['wapi']['jr']['alerts'];



	#	if (empty($x)){ $x = ['No Alerts'];}
		return $x;

}

function Xget_external ($loginfo, $url,string $expected='',array $header=[]) {
		/* tries to geet the url, tests for suc cess and
			for expected result if supplied.
			returns result array on success
			returns false on erro.
			$loginfo is just for Log info
		*/
		$curl = curl_init();

		curl_setopt_array($curl,$this->curl_options());
		curl_setopt($curl,CURLOPT_URL, $url);
		if ($header)
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$aresp = [];
		$success=0;
		$fail = '';
		$tries = 0;

		while (!$success) {
			$success = 1;

			if ($tries > 2){
					//echo "Can't get valid data from ext source  $loginfo";
				Log::error("External failed for $loginfo: $fail.",$aresp);
				return false;
			}
			//Log::info("trying external. Tries:$tries. URL: $url");
			if (! $response = curl_exec($curl)) {
				$success = 0;
				$fail = "No response on $loginfo";
			}
//U::echor ($response,'curl response' );			if ($success ){

			if ($success && $info = curl_getinfo($curl)){
				$httpResult =  trim($info["http_code"]);
		//echo "httpResult: $httpResult" . BR;
				if ($httpResult !== '200'){
					$success = 0;
					$fail = 'Bad return ' . $httpResult;
				}
			} else {
				$success = 0;
				$fail = 'Cannot get httpResult';
			}

			if ($success && !$aresp = json_decode($response, true) ){
				$success = 0;
				$fail = " Failed JSON decode ";
			}

			if ($success &&  $expected && !Utilities::inMultiArray($expected,$aresp)) {
				$success = 0;
				$fail = "Failed expected result $expected";
			}

			if (! $success) {
					++$tries;
					sleep (1);

			} else {
				curl_close($curl);
				//Log::info ("External succeeded for $loginfo.  Tries $tries.");
				return $aresp;
			}

		}

	}

public static function split_coord ($loc) {
	if (!$coord = CS::$coordinates[$loc]){
		die ("Attempt to get coordinates of undefined location $loc");
	}
	[$lat,$long] = explode(',',$coord);
	return [$lat,$long];
}

private function parseRecCamps ($rec){
// parses rec.gov campsite data to determine
// vailability by campground and date
// $rec = [campsites=>siteid=>'availabilities'=>'2023-01-14T00:00:00Z' = 'Rewsereved|Not Avaialbe|Available

// $cgavail = cg => date => ground = avail]
	$cgavail=[];

	if (! isset ($rec['campsites'])){
		LOG::error ("Campground record has no campsites",$rec);
		return false;
	}

	foreach ($rec['campsites']  as $siteid=>$sitedata ){
		$sitename = $sitedata['site'];
		foreach ($sitedata['availabilities'] as $resdate=>$avail){
			if (!isset($cgavail[$resdate]))  $cgavail[$resdate] = 0;
			if ($avail == 'Available'){
				 ++$cgavail[$resdate] ;
			}
		}
	}

	//U::echor($cgavail, 'cgavail');
	return $cgavail;
}
	public function setMtime($cache,$ts){
		$cfile = CS::getCacheFile($cache);
		Log::info ( "Resetting mtime $cfile, $ts") ;
		touch ($cfile,$ts);
	}


}



class Api {

	private $client;

	private $source;
	private $httpHeaders;
	private $base_uri;
	private $headers;
	public function __construct(string $apicode,array $headers=[]){

		$this->base_uri = CS::getURL($apicode);
//echo $this->base_uri . BR;
		$httpHeaders = array(
			'User-Agent' => 'nps-jotr Today/' . VERSION,
			 //'Accept'     => 'application/json',
			);
		$this->headers = $headers;
		$headers = array_merge ($httpHeaders,$headers);

		$this->client = new Client(['base_uri' => $this->base_uri,'timeout' => 2, 'headers' => $headers]);
	}

	public function apiRequest($loginfo,string $relurl='',array $query=[],$retries = 2) {
		// bu9ld thee request
			//Log::info("Starting apiRequest $loginfo");
			$request = new Request('GET',$relurl);
		$tries = 0;
		$einfo = '';
		if (0) {
			echo "base: " . $this->base_uri . BR;
			echo "rel: $relurl" .BR;
			U::echor($query,'query');
		}
		while ($tries <= $retries) {
			try {
				$response = $this->client->send($request,['query' => $query]);
				$status = $response->getStatusCode();
				if ($status !== 200) {
					//Log::notice("No api response for $loginfo",$response);
					throw new \RuntimeException("Server returned $status. ");
				}
				if (!$result = json_decode( $response->getBody(),true)){
					//Log::notice("Empty response for $loginfo; retrying");
					throw new \RuntimeException ("Empty result. ");
				}

				return $result;
				break;
			} catch(GuzzleHttp\Exception\ServerException $e) {
					$einfo = "Server Exception $loginfo (try $tries): ". $e->getMessage();
			} catch (GuzzleHttp\Exception\RequestException $e) {
				$einfo ="Api request exception $loginfo; try $tries.: ". $e->getMessage();
			} catch(GuzzleHttp\Exception\ConnectException $e) {
				$einfo = "Connect Exception $loginfo (try $tries): ". $e->getMessage();
			} catch (\RuntimeException $e) {
				$einfo = "Api error $loginfo. (try $tries): ". $e->getMessage();
			} finally {
				if ($einfo) Log::notice("$loginfo $tries trys. $einfo");
				++$tries;
				sleep(1);
			}
		} #end while loop

		Log::warning ("Api $loginfo failed multiple $tries. " . $einfo);
		return false;

	}

private function eInfo($relurl,$query,&$response,&$e) {
	$info =
		[
			'url' => $this->base_uri,
			'headers' => $this->headers,
			'relurl' => $relurl,
			'query' => $query,
			'code' => $this->getCode($response),
			// 'response' => $response,
			//'message' => $this->getEmessage($e)
		];


	return $info;
}

private function getCode(&$response) {
		if ($response) {
			$code = ['code' =>
		'Status: '
		. $response->getStatusCode()
		. ' ('
		. $response->getReasonPhrase()
		. ')'
		];
		}
		else {
			$code = ['code' =>'No response'];
		}
		return $code;

	}

	private function getEmessage(&$e){
		// return array with error message
		if ($e){
		$resp = [
			'request' => Psr7\Message::toString($e->getRequest()),
			//'response' => Psr7\Message::toString($e->getResponse()??'no response')
			];
		} else {
			$resp = ['resp' =>"No error"];
		}
		return $resp;
	}


}
