<?php
namespace DigitalMx\jotr;


use DigitalMx\jotr\Log;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\InitializeCache;




//END START
########  self::$cacheFilesS #############
/*
	Caches contain all the data retrievedd or stored
	from various sources, both internal and external.
	There is one cache for each source, so minimal re-formatting
	of the raw source.  Caches are all json arrays.

	Caches need to be refreshed periodically.  The refresh
	time for each cache is stored in Defs::$cache_times,
		Refresh time is 0 for caches that are manually updated,
	like admin.  So refresh =
	$limit && (time() - $limit > 0)

	Tried two alternatives:
	Refresh all caches periodically with cron (refresh_caches()).
	Refresh each cache on loading it (refreshCache($cache)).

	Former is bertter because it does the refresh in the background,
	so is invisible to the user.  Otherwise user can get 'hung'
	becausee of possible delays or issues with the refresh.

	*/







class CacheManager {

	private static $sources = [
	'airq' => 'air-quality.p.rapidapi.com/current',
	'airowm' => 'api.openweathermap.org',
	'airnow' => 'airnowapi.org/observation',
	'wapi' => 'api.weatherapi.com forecast',
	'wgov' => 'weather.gov',
	'wgovalerts' => 'weather.gov alerts',

	];

		private static $cacheFiles  = array (
				'admin' => REPO_PATH . "/var/admin.json",
				'airnow' => REPO_PATH . "/var/airnow.json",
				'airowm' => REPO_PATH . "/var/airowm.json",
				'airq' => REPO_PATH . "/var/airq.json",
				//'alerts' => REPO_PATH . "/var/alerts.json",
				'calendar' => REPO_PATH . "/var/calendar.json",
				'camps' => REPO_PATH . "/var/camps.json",
				'campsRec' => REPO_PATH . "/var/campsRec.json",
				'current' => REPO_PATH . "/var/current.json",
				'galerts' => REPO_PATH . "/var/galerts.json",
				'properties' => REPO_PATH . "/var/properties.json",
				'wapi' => REPO_PATH . "/var/wapi.json",
				'wgov' => REPO_PATH . "/var/wgov.json",

			);



	private $wlocs = ['jr','br','cw'];
	private $airlocs = ['jr','br','cw'];

public function __construct() {


}

public function writeCache(string $section,array $z) {
	if (empty($z)){
	Log::error("Writing empty array to $section") ;
	}
	if($this->file_put_contents_locking (self::$cacheFiles[$section],json_encode($z))){
		//Log::info("Writing cache $section");
	} else {
		Log::error("Cannot write cache $section due to lock");
		//die("Error: cannot write $section due to lock file");
	}
}

private function getCacheMtime($section){
	$mtime = filemtime (self::$cacheFiles[$section]);
	return $mtime;
}

public function loadCache ($section) {
	// normally checks for out of date cacghe and rebuilds it.
	// prevent refresh check by setting refresh - false.
	// This is need to prevent endless loop if rebuild includes another load.

#echo "loading cache $section" . BR;

	// see if already loaded
	if (isset($this->$section)){
		return $this->$section;
	}
	// check validity
		if  (!file_exists (self::$cacheFiles[$section]) ) {
			Log::info("rebuilding non-existent cache $section.");
			$this->refreshCache($section,true);
		}



			$ot = $this->over_cache_time($section);
			$limit = Defs::getMaxTime($section);
			$otm = round($ot/60);
			if ($ot>2*$limit){
				Log::notice("Loading $section is stale. $otm minutes");
				//echo ("$section limit $limit ot $ot").BR;
				//$this -> refreshCache($section);
			}


		if (!$y = json_decode ($this->file_get_contents_locking(self::$cacheFiles[$section]), true)) {
			Log::error("Failed to json decode cache $section.  ");

		}

		if (empty($y)) {
			Log::error("Failed to load cache $section.  Returning empty.");
			return [];
		}
		//Utilities::echor($y,$section,NOSTOP) . BR;
		$this->$section = $y;
		return $y;
}

private function over_cache_time($section) {
	//global $Defs;
	/* dies if file not exists
		0 if mtime is under the limit
		diff if mtime is over the limit by diff
		XXX Returns true if time is within 5 minutes of limit
	*/

	$limit = Defs::getMaxTime($section) ; #in seconds
	if (!$limit){ return 0;}
	if (!file_exists(self::$cacheFiles[$section])){return 2*$limit;}
	$filetime = filemtime (self::$cacheFiles[$section]);
	$age = time() - $filetime;
	$margin = 0;
	if ( $age > ($limit - $margin) ) return $age; #in seconds

//	echo "$section: limit $limit; diff $diff;" . BR;
	return 0;
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

private function curl_options () {

	$agent = 'Mozilla/5.0 (NPS.gov/jotr app)';


	$options = [
	CURLOPT_USERAGENT => $agent,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 10,
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",


	];

	return $options;
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
	$x=[];
	$src = 'airnow';
	$locs = $this->airlocs;

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = "https://www.airnowapi.org/aq/observation/latLong/current/?format=application/json&latitude=$lat&longitude=$lon&distance=25&API_KEY=" . Defs::getKey('airnow');
				$expected = 'AQI'; #field to test for good result
		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		} //if one loc fails, fail the whole thing

		$x[$loc] = $aresp;
	} # next loc

	$this->writeCache($src,$x);
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

	 if (!$r = $this->loadCache('wapi',false) ) {
	 	Log::error ("Could not load cache wapi");
	 	return [];
	 }
 //	Utilities::echor($r,'From wapi',STOP);
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
//	Utilities::echor($r,'get wgalerts',NOSTOP);
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
		$this->writeCache('galerts',$y);
//Utilities::echor($y,'from external  alerts', NOSTOP);
	return $y;
}

public function rebuildCampsRec() {
	$cache = 'campsRec';
	if (! file_exists(self::$cacheFiles[$cache])) $this->initializeCache($cache);
		// for now just:
		touch (self::$cacheFiles['campsRec']);


		// if (! 1 #||  load rec.gov data
// 		){
// 			Log::info("Cannot retrieve opens from rec.gov");
// 			// do leave for another time.
// 			return false;
// 		}
		// update opens from fretried data
		//$this->writeCache('cgres',$current_res);
		Log::info("Touched cgres");
		return true;

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

	//$this->writeCache($src,$x);
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
		$loginfo = "$src:$loc";
		//[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = "https://api.weather.gov/gridpoints/" . Defs::getGridpoints($loc) . '/forecast' ; #./forecast
		#$url = "https://api.weather.gov/points/$lat,$lon";
		$expected = 'properties';


		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed expected '$expected' for $loginfo. Skipped.");
			continue;
		} //if one loc fails, skip and get next

		$x[$loc] = $aresp;
	} # next loc

	// using merge instead of write because some sites may have failed.
	if (empty($x)){
		Log::notice("No results for wgov. Not updating cache.");
	} else {
		$this->mergeCache($src,$x);
		Log::info("Merged data into cache wgov");
	}
	$this->wgovupdate = strtotime($x['jr']['properties']['updated']);
	return $x;
}

public function mergeCache($cache,$merge){
// merges data into cache, unless data is empty
		if (file_exists (self::$cacheFiles[$cache])){
			$x = $this->loadCache($cache,false);
		} else {
			$x=[];
		}
//		Utilities::echor ($x, "merge: Loaded cache $cache");
//		Utilities::echor ($merge,'merge: Data to merge');
		if (! empty ($merge)){ #if empty you're done
			$y = array_merge($x,$merge);
// 		Utilities::echor ($y,'merged cache');
			$this->writeCache($cache,$y);
		}
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
		//Utilities::echor($aresp,'current aresp');
		if (is_null($aresp['properties']['temperature']['value'] )) {
				Log::warning ("Received null temp for $loginfo",$aresp['properties']['temperature']);
				return [];
		}

		$x[$loc] = $aresp;
	} #next loc
	Log::info("Saved updated cache $src");
	$this->writeCache($src,$x);
	return $x;
}

private function rebuild_cache_airowm() {
	$x=[];
	$src = 'airowm';
	$locs = $this->airlocs;

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = "http://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid=" . Defs::getKey('openweathermap');

		$expected = '';

		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		} //if one loc fails, fail the whole thing

		$x[$loc] = $aresp;
	} # next loc

	$this->writeCache($src,$x);
	Log::info("Saved updated cache $src");

}

private function rebuild_cache_calendar () {
	if (! file_exists(self::$cacheFiles['calendar'])) $this->initializeCache['calendar'];
	$x = $this->loadCache('calendar');
	$x = $this->Cal -> filter_calendar($x,0);
	$this->writeCache('calendar',$x);
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

	$this->writeCache($src,$x);
	Log::info("Saved updated cache $src");

}

public function rebuild_cache_wapi(array $locs=[] ) {
	$x=[];
	$src = 'wapi';
	if (empty($locs)){$locs = $this->wlocs;}

	foreach ($locs as $loc) {
		[$lat,$lon] = $this -> split_coord($loc);
		$curl_header = [];

		$url = 'http://api.weatherapi.com/v1/forecast.json?key=' . Defs::getKey('weatherapi') . '&q='. Defs::getCoords($loc) . '&days=3&aqi=yes&alerts=yes';
	//echo "url: $url". BRNL;
		$expected = '';
		$loginfo = "$src:$loc";
		if (!$aresp = $this->get_external($loginfo,$url, $expected, $curl_header) ) {
			Log::notice("Failed $loginfo.  Rebuild aborted."); return [];
		} //if one loc fails, fail the whole thing

		$x[$loc] = $aresp;
	} # next loc

	$this->writeCache($src,$x);
	Log::info("Saved updated cache $src");
	$this->wapiupdate = $x['jr']['current']['last_updated_epoch'];
	return $x;
}




public function rebuild_properties() {
	$locs=['br','cw','jr','kv','lhrs','pdx','shasta'];
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
	$this->writeCache($src,$x);
	Log::info("Retrieved locs for cache $src");
	return true;
}

public function refreshAllCaches($force=false) {
	Log::info ("Starting all cache refresh cycle");

/* refreshes all the external caches, if they are due

*/
	foreach (array_keys(self::$cacheFiles) as $cache){
		$this->refreshCache($cache);
	}

	#	$this -> rebuild_properties('jr');
	Log::info ("Completed cache refresh cycle");

}
private function initializeCache($cache) {
	switch ($cache) {
		case 'admin':
			$this->writeCache($cache,InitializeCache::$$cache);
			break;
		case 'calendar':
			$this->writeCache($cache,InitializeCache::$$cache);
			break;
		case 'camps':
			$this->writeCache($cache,InitializeCache::$$cache);
			break;
		case 'campsRec':
			$this->writeCache($cache,InitializeCache::$$cache);
			break;

		default:
			// do nothing

	}
	return true;
}

public function refreshCache($cache,$force=0) {
	// refresh individual cache on demand.
	/* back and forth over refresh all via cron
	vs refrewsh on deman when loaded.  Problem with
	doing it on demand is that it may tie up script
	for a while due to sleep cycles for failed curl
	attempts.  Better to run refresgh as a background
	job by cron.  So use 'refresh_caches' instead of this

	Force = 0 -> run normally
	Force = 1 -> run refresh regardless of ot


	*/
	$ot = false;
	if (!file_exists(self::$cacheFiles[$cache])) {
		$force=true; $ot=1;
	} else {
		$ot = $this->over_cache_time($cache);
	}
	if (!$ot &&  ! $force ) return true;
	Log::info("Starting refresh on $cache");
	switch ($cache) {
		case 'admin':
			if (!file_exists(self::$cacheFiles[$cache])) $this->initializeCache($cache);
			break;

		case 'airnow':
			$this->rebuild_cache_airnow();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;

		case 'airowm':
			$this->rebuild_cache_airowm();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;

		case 'airq':
			$this->rebuild_cache_airq();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;

		case 'calendar':
			$this->rebuild_cache_calendar(); #filter out old stuff
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;

	case 'camps':
			$this->initializeCache('camps'); #rebuild from radmin
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;

		case 'campsRec':

			$this->rebuildCampsRec(); #rebuild from rec.gov
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;

		case 'current':
			$this->rebuild_cache_current();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;

		case 'galerts':
			$this->rebuild_cache_galerts();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;


		case 'wapi':
			$this->rebuild_cache_wapi();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;

		case 'wgov':
			$this->rebuild_cache_wgov();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			echo "$cache Refreshed." . BR;
			break;


		default:
			Log::error ("Attempt to refresh unknown cache $cache");
			return false;
	}
	return true;

}

private function rebuildCamps() {
	/*reinitialize, since array only build from admin */
	$this->writeCache('camps',InitializeCache::$camps);
}

public function set_properties (array $locs) {
	// gets meta data for each location by lat,lon
	// saves it in data file properties.json
	$src = 'props';
	$x = array('src'=>$src);
	$x['update'] = time();



	if ( ! $r = $this->get_external ($src,$locs)) return false;
	//Utilities::echor($r,'From external',STOP);

	foreach ($r as $loc => $d){	//uses weather.gov api directly
		$y[$loc] = $d['properties'];
	} #end foreach
// Utilities::echor($y,'properties',STOP);

	$this->writeCache('properties',$y);
	echo "Properties updated" . BRNL;
	return true;


}

public function format_galert($galerts){


#	Utilities::echor($cache);
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


			$x[] =$alert;

		}
	#	if (empty($x)){ $x = ['No Alerts'];}
		return $x;

}

function get_external ($loginfo, $url,string $expected='',array $header=[]) {
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
			//Utilities::echor($aresp,"Here's what I got for $loginfo:");
			Log::info("trying external. Tries:$tries. URL: $url");
			if ($tries > 2){
					//echo "Can't get valid data from ext source  $loginfo";
				Log::error("External failed for $loginfo: $fail.",$aresp);
				return false;
			}
			if (! $response = curl_exec($curl)) {
				$success = 0;
				$fail = "No response on $loginfo";
			} else { $success = 1;}


			if ($success && !$aresp = json_decode($response, true) ){
				$success = 0;
				$fail = " Failed JSON decode ";
			} else { $success = 1;}

			if ($success &&  $expected && !Utilities::inMultiArray($expected,$aresp)) {
				$success = 0;
				$fail = "Failed expected result $expected";
			}else { $success = 1;}

			if (! $success) {
					++$tries;
					sleep (1);

			} else {
				curl_close($curl);
				Log::info ("External succeeded for $loginfo.  Tries $tries.");
				return $aresp;
			}

		}

	}

private function split_coord ($loc) {
	if (!$coord = Defs::$coordinates[$loc]){
		die ("Attempt to get coordinates of undefined location $loc");
	}
	[$lat,$long] = explode(',',$coord);
	return [$lat,$long];
}



}
