<?php
namespace DigitalMx\jotr;

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
public static $cacheFiles  = array (
				'admin' => REPO_PATH . "/var/admin.json",
				'airnow' => REPO_PATH . "/var/airnow.json",
				'airowm' => REPO_PATH . "/var/airowm.json",
				'airq' => REPO_PATH . "/var/airq.json",
				//'alerts' => REPO_PATH . "/var/alerts.json",
				'calendar' => REPO_PATH . "/var/calendar.json",
				'camps' => REPO_PATH . "/var/camps.json",
				'current' => REPO_PATH . "/var/current.json",
				'galerts' => REPO_PATH . "/var/galerts.json",
				'properties' => REPO_PATH . "/var/properties.json",
				'wapi' => REPO_PATH . "/var/wapi.json",
				'wgov' => REPO_PATH . "/var/wgov.json",
);


public static $gridpoints = [
	'hq' => 'VEF/72,12',
	'jr' => 'PSR/13,102',
	'cw'=>	'PSR/23,89',
	'br' => 'PSR/4,107',
	'kv' => 'PSR/11,99',



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

	public static $api_keys = array (
		'airnow' => '7FB4BEFF-A568-4FE4-8E67-F1EE36B5C04B',
		 'weatherapi' => '098273e9f48149029c4141515220107',
        'openweathermap' => '8f15b8d7833c050a41538d5b0ee4204a',
        'iqair' => '8e4fb9bb-1502-4711-b3d7-f98447082dcf',
        'ridb' => '3c4e8e8a-10d0-4512-8459-c79fe9d9a8b1',

      );

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

	);


	public static $urls = [
	'airq' => 'air-quality.p.rapidapi.com/current',
	'airowm' => 'api.openweathermap.org',
	'airnow' => 'airnowapi.org/observation',
	'wapi' => 'api.weatherapi.com forecast',
	'wgov' => 'weather.gov',
	'wgovalerts' => 'weather.gov alerts',
	'ridb' => 'ridb.recreation.gov/api/availability',

	];


/* time before refresh in minutes.  0 means
// cache is static except for update by
// the admin screen.  Caches checked every 60
mins,
*/
	public static $cacheTimes  = array (

				'calendar' => 60*6,
				'admin' => 0,
				'properties' => 0, // manual only
				'wgov' => 90,
				'wapi' => 90,
				'airq' => 0,
				'airnow' => 235,
				'airowm' => 0,
				'alerts' => 0,
				'galerts' => 110,

				'camps' => 0,
				'current' => 55,


			);


/* list of pages for rotation in the tv page
// key is suffix of id of the page in condensed.tpl
	e.g, "<div id='page-suffix' ..."

*/


	public static function getCacheFile($cache){
		return self::$cacheFiles[$cache] ?? '';
	}
	public static function getGridpoints($loc){
		return self::$gridpoints[$loc] ?? '';
	}
	public static function getCoords($loc) {
		return self::$coordinates[$loc] ?? '';
	}

	public static function getKey($site){
		return self::$api_keys[$site] ?? '';
	}

	public static function getFacility($fcode){
		return self::$facilityCodes[$fcode];
	}
	public static function getURL($src) {
		return self::$urls[$src];
	}

	public static function getMaxtime($section) {
		return (60 * self::$cacheTimes[$section]);
	}


	public static function getSourceName($source) {

		$result = self::$sources[$source] ?? "$source name not found";
		return $result;
	}

}
