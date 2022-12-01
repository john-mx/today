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
class Definitions {

	// File Definitions
	public static $Files = [
		'passwords' => 'passwords.ini',
	];


public static $scale_color = [

		'Low' => 'green',
		'Good' => '#CCFFCC',
		'Moderate' => 'yellow',
		'Unhealthy for Sensitive Groups' => 'orange',
		'High' => "orange",
		'Unhealthy' => 'red',
		'Very High' => 'red',
		'Very Unhealthy' => 'purple',
		'Extreme' => 'red',
		'Hazardous' => 'maroon',

	];

public static $firewarn = [

		'Low' => 'not bad',
		'Moderate' => 'normal',
		'High' => "a bit high",
		'Very High' => 'no fires',
		'Extreme' => 'carry an extinguisher',

'Low' =>  'When the fire danger is "low" it means that fuels do not ignite easily from small embers, but a more intense heat source, such as lightning, may start fires in duff or dry rotten wood.  Fires in open, dry grasslands may burn easily a few hours after a rain, but most wood fires will spread slowly, creeping or smoldering.  Control of fires is generally easy.',
'Moderate' =>  'When the fire danger is "moderate" it means that fires can start from most accidental causes, but the number of fire starts is usually pretty low.  If a fire does start in an open, dry grassland, it will burn and spread quickly on windy days.  Most wood fires will spread slowly to moderately.  Average fire intensity will be moderate except in heavy concentrations of fuel, which may burn hot.  Fires are still not likely to become serious and are often easy to control. ',
'High'  =>  'When the fire danger is "high", fires can start easily from most causes and small fuels (such as grasses and needles) will ignite readily.  Unattended campfires and brush fires are likely to escape.  Fires will spread easily, with some areas of high-intensity burning on slopes or concentrated fuels.  Fires can become serious and difficult to control unless they are put out while they are still small.',
'Very High'  =>  'When the fire danger is "very high", fires will start easily from most causes.  The fires will spread rapidly and have a quick increase in intensity, right after ignition.  Small fires can quickly become large fires and exhibit extreme fire intensity, such as long-distance spotting and fire whirls.  These fires can be difficult to control and will often become much larger and longer-lasting fires.',
'Extreme' => 'When the fire danger is "extreme", fires of all types start quickly and burn intensely.  All fires are potentially serious and can spread very quickly with intense burning.  Small fires become big fires much faster than at the "very high" level.  Spot fires are probable, with long-distance spotting likely.  These fires are very difficult to fight and may become very dangerous and often last for several days.',



	];

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
];

	public static $api_keys = array (
		'airnow' => '7FB4BEFF-A568-4FE4-8E67-F1EE36B5C04B',
		 'weatherapi' => '098273e9f48149029c4141515220107',
        'openweathermap' => '8f15b8d7833c050a41538d5b0ee4204a',
        'iqair' => '8e4fb9bb-1502-4711-b3d7-f98447082dcf',

      );




	public static function uv_scale ($uv) {
		// sets uv name based on index
		if (!is_numeric($uv)){
			throw new Exception ("non-numeric uv index");
		}
		if ($uv <= 2.9) return "Low";
		if ($uv <= 5.9) return "Moderate";
		if ($uv <= 7.9) return "High";
		if ($uv <= 10.9) return "Very High";
		return "Extreme";


	}

	public static $airwarn = array (
		'Good' => '',
		'Moderate' => 'Unusually sensitive people should consider reducing prolonged or heavy exertion outdoors.',
		'Unhealthy for Sensitive Groups' => 'Active children and adults, and people with lung disease, such as asthma, should reduce prolonged or heavy exertion outdoors.',
		'Unhealthy' => 'Active children and adults, and people with lung disease, such as asthma, should avoid prolonged or heavy exertion outdoors. Everyone else, especially children, should reduce prolonged or heavy exertion outdoors.',
		'Very Unhealthy' => 'Active children and adults, and people with lung disease, such as asthma, should avoid all outdoor exertion. Everyone else, especially children, should avoid prolonged or heavy exertion outdoors. ',
	);


	public static $uvwarn = array (

		'Low' => ' No protection needed. You can safely stay outside using minimal sun protection.',
		'Moderate' => 'Protection needed. Seek shade during late morning through mid-afternoon. When outside, generously apply broad-spectrum SPF-15 or higher sunscreen on exposed skin, and wear protective clothing, a wide-brimmed hat, and sunglasses.',
		'High' => 'Protection needed. Seek shade during late morning through mid-afternoon. When outside, generously apply broad-spectrum SPF-15 or higher sunscreen on exposed skin, and wear protective clothing, a wide-brimmed hat, and sunglasses.',
		'Very High' => 'Extra protection needed. Be careful outside, especially during late morning through mid-afternoon. If your shadow is shorter than you, seek shade and wear protective clothing, a wide-brimmed hat, and sunglasses, and generously apply a minimum of  SPF-15, broad-spectrum sunscreen on exposed skin.',
		'Extreme' => 'Extra protection needed. Be careful outside, especially during late morning through mid-afternoon. If your shadow is shorter than you, seek shade and wear protective clothing, a wide-brimmed hat, and sunglasses, and generously apply a minimum of  SPF-15, broad-spectrum sunscreen on exposed skin.',


	);

	public static function aq_scale ($uv) {
		// sets uv name based on index
		if (!is_numeric($uv)){
			throw new Exception ("non-numeric uv index");
		}
		if ($uv <= 51) return "Good";
		if ($uv <= 100) return "Moderate";
		if ($uv <= 150) return "Unheadly for Sensitive Groups";
		if ($uv <= 200) return "Unhealthy";
		if ($uv <= 300) return "Very Unhealthy";

		return "Hazardous";


	}


	public static $sitenames = [
		'ic' => 'Indian Cove',
		'jr' => 'Jumbo Rocks',
		'sp' => 'Sheep Pass (group)',
		'hv' => 'Hidden Valley',
		'be' => 'Belle',
		'wt' => 'White Tank',
		'ry' => 'Ryan',
		'br' => 'Black Rock',
		'cw' => 'Cottonwood',
		'hq'	=> 'Twentynine Palms',
		'kv' => 'Keys View',
		'pdx' => 'Portland, OR',
	];

	public static $campsites = [
		'ic' => 101,
		'jr' => 124,
		'sp' => 6,
		'hv' => 50,
		'be' => 18,
		'wt' => 15,
		'ry' => 31,
		'br' => 99,
		'cw' => 62,
	];

	public static $campfeatures = [
		'ic' => 'G',
		'jr' => '',
		'sp' => 'G',
		'hv' => '',
		'be' => '',
		'wt' => '',
		'ry' => '',
		'br' => 'H,D,W',
		'cw' => 'D,W,',
	];

	public static $cgstatus = [
		'First',
		'Reservation',
		'Closed',
	];

	public static $sources = [
	'airq' => 'air-quality.p.rapidapi.com/current',
	'airowm' => 'api.openweathermap.org',
	'airnow' => 'airnowapi.org/observation',
	'wapi' => 'api.weatherapi.com forecast',
	'wgov' => 'weather.gov',
	'wgovalerts' => 'weather.gov alerts',

	];

		public static $caches  = array (
				'admin' => REPO_PATH . "/data/admin.json",
				'airnow' => REPO_PATH . "/data/airnow.json",
				'airowm' => REPO_PATH . "/data/airowm.json",
				'airq' => REPO_PATH . "/data/airq.json",
				'alerts' => REPO_PATH . "/data/alerts.json",
				'calendar' => REPO_PATH . "/data/calendar.json",
				'properties' => REPO_PATH . "/data/properties.json",
				'wapi' => REPO_PATH . "/data/wapi.json",
				'wgov' => REPO_PATH . "/data/wgov.json",
				'galerts' => REPO_PATH . "/data/galerts.json",
				'cgopen' => REPO_PATH . "/data/cgopen.json",
				'camp' => REPO_PATH . "/data/camp.json",
			);

// time before refresh in minutes.  0 means
// cache is static except for update by
// the admin screen.  No outside retrieval.
	public static $cache_times  = array (

				'calendar' => 60*12,
				'admin' => 0,
				'properties' => 0, // not using this
				'wgov' => 240,
				'wapi' => 240,
				'airq' => 240,
				'airnow' => 240,
				'airowm' => 240,
				'alerts' => 0,
				'galerts' => 120,
				'cgopen' =>240,
				'camp' => 0,



			);

	private static $moons = array (
			'New Moon' => '0.gif',
			'Waxing Crescent' => '1.gif',
			'First Quarter' => '2.gif',
			'Waxing Gibbous' => '3.gif',
			'Full Moon' => '4.gif',
			'Waning Gibbous' => '5.gif',
			'Third Quarter' => '6.gif',
			'Waning Crescent' => '7.gif',
			'Last Quarter' => '6.gif'
		);

	public static function uv_warn($uvd) {
		return self::$uvwarn[$uvd] ?? 'not defined';
	}

	public static function air_warn($uvd) {
		return self::$airwarn[$uvd] ?? 'not defined';
	}

	public static function scale_color($uvd){
		return self:: $scale_color[$uvd] ?? '';
	}
	public static function get_color($x){
		return self:: $scale_color[$x] ?? '';
	}
	public static function fire_warn($fdesc) {
		return self::$firewarn[$fdesc];
	}

	public static function getEcode($ecode) {
		return self::$ecodes[$ecode];
	}

	public function getGridpoints($loc){
		return self::$gridpoints[$loc] ?? '';
	}
	public static function getCoords($loc) {
		return self::$coordinates[$loc] ?? '';
	}

	public static function getKey($site){
		return self::$api_keys[$site] ?? '';
	}


	public static function getMaxtime($section) {
		return (60 * self::$cache_times[$section] );
	}

	public static function getMoonPic($phase) {
		return self::$moons[$phase] ?? 'error.png';
	}
	public static function getSourceName($source) {

		$result = self::$sources[$source] ?? "$source name not found";
		return $result;
	}
	public static function getLocName($loc) {

		$result = self::$sitenames[$loc] ?? "$loc name not found";
		return $result;
	}
}
