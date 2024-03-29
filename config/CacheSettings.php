<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Utilities as U;

/* This file contains tables of names, lists, etc
	used throughout the site and grouped into
	  Definitions (db tables, )
	  Definitions_Email (status codes, )
	  Definitions_News (sections, types,
	  Definitions_Member (status, aliases, security levels)
	Most are public static vars, so call like
	  Definitions::$dbTable

*/
class CacheSettings {
// stations for current weather

/* apis
JUMBO ROCKWS
	https://api.weather.gov/points/33.9917,-116.1402
 "forecastZone": "https://api.weather.gov/zones/forecast/CAZ560",
"county": "https://api.weather.gov/zones/county/CAC065",
"fireWeatherZone": "https://api.weather.gov/zones/fire/CAZ230",

Cottonwood
https://api.weather.gov/points/33.7485,-115.8211
Indian cove

Black rock
https://api.weather.gov/points/34.0733,-116.3907
29 palms

Keys View
https://api.weather.gov/points/33.9272,-116.1875



hq
https://api.weather.gov/points/
*/

// weather.gov grid points
private static $cacheFiles  = array (
				'admin' => REPO_PATH . "/var/admin.json",
				'airnow' => REPO_PATH . "/var/ext/airnow.json",
				'airowm' => REPO_PATH . "/var/ext/airowm.json",
				'airq' => REPO_PATH . "/var/ext/airq.json",
				'calendar' => REPO_PATH . "/var/calendar.json",
				'camps' => REPO_PATH . "/var/camps.json",
				'current' => REPO_PATH . "/var/ext/current.json",
				'npscal'=>REPO_PATH . "/var/ext/npscal.json",
				'cga' => REPO_PATH . "/data/cga.json",
				'props' => REPO_PATH . "/data/properties.json",
			//	'tours' => REPO_PATH . "/var/ext/tours.json",
				'wapi' => REPO_PATH . "/var/ext/wapi.json",
				'wgalerts' => REPO_PATH . "/var/ext/wgalerts.json",
				'wgov' => REPO_PATH . "/var/ext/wgov.json",
				'refRequest' => REPO_PATH . "/var/ext/refRequest",
				'mergecal' => REPO_PATH . "/var/mergecal.json",

				'npscalraw' => REPO_PATH . "/var/raw/npscalraw.json",
				'reccampsavail' => REPO_PATH . "/var/raw/reccampsavail.json",
				'willy' => REPO_PATH . "/var/ext/willy.json",
);


//

public static $gridpoints = [
	'hq' => 'VEF/72,12',
	'jr' => 'PSR/13,102',
	'cw'=>	'PSR/23,89',
	'br' => 'PSR/4,107',
	'kv' => 'PSR/11,99',
	'lhrs'=>'PSR/11,103',
	'pdx'=>'PQR/112,103',
	];

public static $stations = [
'lhrs' => 'LTHC1',
];



public static $coordinates = [
// no spaces!
	'jr' => '33.9917,-116.1402',
	'br' => '34.0733,-116.3907',
	'kv' => '33.9272,-116.1875',
	'hq' => '34.1348,-116.0815',
	'cw' => '33.7485,-115.8211',
	'pdx' => '45.5152,-122.6784',
	'shasta' => '41.3099,-122.3106',
	'denver' => '39.7392,-104.9903',
	'lhrs' => '34.01779,-116.18857',
];

/* API keys Moved to .env file


*/




// recreation.gov facility codes
	public static $facilityCodes = array (
		'jr' => '272300',
		'hv' => '10005775',
		'wt' => '10005778',
		'icg' => '10005779',
		'ry' => '10056207',
		'sp' => '232470',
		'ic' => '232472',
		'br' => '232473',
		'bre' => '234723',
		'be' => '258941',
		'cw' => '272299',
		'krtour' => '300004', #keys ranch tour

	);


	public static $urls = [
	'airq' => 'https://air-quality.p.rapidapi.com/current/airquality',
	'airnow' => 'https://www.airnowapi.org/aq/observation/latLong/current/',
	'wapi' => 'https://api.weatherapi.com/v1/forecast.json',
	'wgov' => 'https://api.weather.gov/gridpoints/',
	'wgalerts' => 'https://api.weather.gov/alerts/active/zone/',
	'ridb' => 'https://ridb.recreation.gov/api/availability/',
	'ridb2'=>'https://ridb.recreation.gov/api/v1/',
	'props' => 'https://api.weather.gov/points/',
	'airnowfc' => 'https://www.airnowapi.org/aq/forecast/latLong/',
	'airowm' => 'https://api.openweathermap.org/data/3.0/onecall',
	'willy' => 'https://api.willyweather.com/v2/',
	'cga'=>'https://ridb.recreation.gov/api/v1/', // same as ridb2
	'nps' => 'https://developer.nps.gov/api/v1/events',
	'current' => 'https://api.weather.gov/stations/',

	];

/*
"https://www.airnowapi.org/aq/observation/latLong/current/?format=application/json&latitude=$lat&longitude=$lon&distance=25&API_KEY=" . CS::getKey('airnow');
*/
/*
	time before refresh in minutes.
	0 means cache not automatically refreshed
	-1 means cache is always refreshed on a refresh cyle
	Caches checked periodically by cron running refreshCaches.

*/
	private static $cacheTimes  = array (

				'calendar' => 110,  //filtered to remove expired entries
				'admin' => 0, // always manual
				'props' => 0, // manual only
				'wgov' => 220,
				'wapi' => 220,
				'airq' => 0, // not used
				'airnow' => 232,
				'airowm' => 60,
				'wgalerts' => 110, // weather.gov alerts
				'npscal' => 8*60,
				'tours' => 0, // 0 until code set.  then -1
				'camps' => 20, // every 20 mins
				'current' => 30,
				'willy'=>12*60,
				//'refRequest' => 60, // tracks last refresh time
				//'mergecal' => 0,
				'cga'=> 0,

			);


/* list of pages for rotation in the tv page
// key is suffix of id of the page in condensed.tpl
	e.g, "<div id='page-suffix' ..."

*/


	public static function getCacheFile($cache){
		return self::$cacheFiles[$cache] ?? false;
	}
	public static function getGridpoints($loc){
		return self::$gridpoints[$loc] ?? '';
	}
	public static function getCoords($loc) {
		return self::$coordinates[$loc] ?? '';
	}

	public static function getKey($site){
		$envsite = $site . '_api';
		if (!$key = $_ENV[$envsite] ) {
			echo "No api key for site $site in envapi:";
			U::echor($_ENV);
			exit;
		}
		return $key;
		//return self::$api_keys[$site] ?? '';
	}

	public static function getFacility($fcode){
		return self::$facilityCodes[$fcode];
	}
	public static function getURL($src) {
		return self::$urls[$src];
	}

	public static function getCacheLimit($section) {
		return ( self::$cacheTimes[$section]);
	}

	public static function getCacheList () {
		$clist = array_keys(self::$cacheTimes);
		sort ($clist);
		return $clist;
	}
	public static function getStation($loc){
		return self::$stations[$loc] ?? "$loc not found";
	}

	public static function getSourceName($source) {

		$result = self::$sources[$source] ?? "$source name not found";
		return $result;
	}

}
