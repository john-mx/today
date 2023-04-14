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
public static $files = [
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

public static $firecolor = [
		'Low' => 'green',
		'Moderate' => 'blue',
		'High' => "yellow",
		'Very High' => 'orange',
		'Extreme' => 'red',



	];

public static $firewarn = [

		'Low' => 'not bad',
		'Moderate' => 'normal',
		'High' => "a bit high",
		'Very High' => 'no fires',
		'Extreme' => 'carry an extinguisher',
];

private static $firewarnlong = [
'Low' =>  'When the fire danger is "low" it means that fuels do not ignite easily from small embers, but a more intense heat source, such as lightning, may start fires in duff or dry rotten wood.  Fires in open, dry grasslands may burn easily a few hours after a rain, but most wood fires will spread slowly, creeping or smoldering.  Control of fires is generally easy.',
'Moderate' =>  'When the fire danger is "moderate" it means that fires can start from most accidental causes, but the number of fire starts is usually pretty low.  If a fire does start in an open, dry grassland, it will burn and spread quickly on windy days.  Most wood fires will spread slowly to moderately.  Average fire intensity will be moderate except in heavy concentrations of fuel, which may burn hot.  Fires are still not likely to become serious and are often easy to control. ',
'High'  =>  'When the fire danger is "high", fires can start easily from most causes and small fuels (such as grasses and needles) will ignite readily.  Unattended campfires and brush fires are likely to escape.  Fires will spread easily, with some areas of high-intensity burning on slopes or concentrated fuels.  Fires can become serious and difficult to control unless they are put out while they are still small.',
'Very High'  =>  'When the fire danger is "very high", fires will start easily from most causes.  The fires will spread rapidly and have a quick increase in intensity, right after ignition.  Small fires can quickly become large fires and exhibit extreme fire intensity, such as long-distance spotting and fire whirls.  These fires can be difficult to control and will often become much larger and longer-lasting fires.',
'Extreme' => 'When the fire danger is "extreme", fires of all types start quickly and burn intensely.  All fires are potentially serious and can spread very quickly with intense burning.  Small fires become big fires much faster than at the "very high" level.  Spot fires are probable, with long-distance spotting likely.  These fires are very difficult to fight and may become very dangerous and often last for several days.',



	];



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
		'Good' => 'Safe for everyone.',
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
	public static $uvwarn_min = array (

		'Low' => ' No protection needed.',
		'Moderate' => 'Use SPF-15 sunscreen, a wide-brimmed hat, and sunglasses outside.',
		'High' => 'Seek shade during late morning through mid-afternoon.  Use SPF-15 sunscreen, a wide-brimmed hat, and sunglasses outside.',
		'Very High' => 'If your shadow is shorter than you, seek shade.  Use SPF-15 sunscreen, a wide-brimmed hat, and sunglasses outside.',
		'Extreme' => 'Extra protection needed. Avoid being outside late morning through mid-afternoon. If your shadow is shorter than you, seek shade. Use SPF-15 sunscreen, a wide-brimmed hat, and sunglasses.',


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

	// used to show age of live data like campgrounds
// time in hours
public static $data_timeouts = [
	'newest' => 1,
	'new' => 6,
];


// tags used to mark stale campground open sites
	private static $staletags = array(
		'0' => '',
		'1' => "<span class= 'dk-orange'>?</span>",
		'2' => "<span class='red'>??</span>",
	);

/*
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
*/
	// nasa transparent images
		private static $moons = array (
			'New Moon' => '366a.png',
			'Waxing Crescent' => '361a.png',
			'First Quarter' => '362a.png',
			'Waxing Gibbous' => '363a.png',
			'Full Moon' => '364a.png',
			'Waning Gibbous' => '365a.png',
			'Third Quarter' => '367a.png',
			'Waning Crescent' => '367a.png',
			'Last Quarter' => '368a.png'
		);



	public static $equipmentCodes = array (
	"Tent" => "Tm",
	"RV" => "R",
	"Trailer" => "L",
	"PICKUP CAMPER" => "C",
	"CARAVAN/CAMPER VAN" => "C",

	"SMALL TENT" => "Ts",
	"LARGE TENT OVER 9X12`" => "Tl",
	"VEHICLE" => "V",
	"FIFTH WHEEL" => "L",
	"POP UP" => "U",
);


	public static function uv_warn($uvd) {
		return self::$uvwarn_min[$uvd] ?? 'not defined';
	}

	public static function getAirWarn($x) {
		return self::$airwarn[$x] ?? 'not defined';
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
	public static function get_firecolor($x) {return self:: $firecolor[$x] ?? '';}



	public static function getMoonPic($phase) {
		return '/images/moon-nasa/' . self::$moons[$phase] ?? 'error.png';
	}


	public static function getFile($f) {
		return self::$files[$f] ?? '';
	}

	public static function getTimeout($to) {
		return self::$data_timeouts[$to];
	}
	public static function getStaleLabel($to) {
		return self::$stale_labels[$to];
	}

	public static function getFireKeys(){
		return array_keys(self::$firewarn);
	}

	public static function getStaleTag($x){
		return self::$staletags[$x] ?? '';
	}

	public static function getEquipCode($eq) {
		return self::$equipmentCodes[$eq] ;
	}
}
