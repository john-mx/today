<?php
namespace DigitalMx\jotr;


class LocationSettings {

private static $gridpoints = [
	'hq' => 'VEF/72,12',
	'jr' => 'PSR/13,102',
	'cw'=>	'PSR/23,89',
	'br' => 'PSR/4,107',
	'kv' => 'PSR/11,99',



	];

private static $coordinates = [
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

private static $facilityCodes = array (
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

private static $locnames = [
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
		'lh' => 'Lost Horse Ranger Station',
		'jtvc' => 'Joshua Tree Visitor Center',
		'29vc' => 'Joshua Tree Cultural Center',
		'cwvc' => 'Cottonwood Visitor Center',
		'brvc' => 'Black Rock Nature Center',
		'park' => 'Park',
		'hqvc' => 'Park Headquarters',
		'none' => 'None',
		'cv' => 'Coachella Valley',
		'shasta' => "Mt Shasta",

	];

private static $campsites = [
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

	private static $campfeatures = [
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

private static $campfees = [
		'ic' => 25,
		'jr' => 20,
		'sp' => '50',
		'hv' => 15,
		'be' => 15,
		'wt' => 15,
		'ry' => 20,
		'br' => 25,
		'cw' => 25,
	];

	private static $camps = [
		'ic'=>[
			'type' => 'cg',
			'sites' => 101,
			'name' => 'Indian Cove Campground',
			'features' => 'G',
			'fee' => 25,
			'coordinates' => '',
			'facility' => '232472',
			'gridpoints' =>'',
			],
		'jr' => [
			'type' => 'cg',
			'sites' => 124,
			'name' => 'Jumbo Rocks Campground',
			'features' => '',
			'fee' => 20,
			'coordinates' => '33.9917,-116.1402',
			'facility' => '272300',
			'gridpoints' =>'PSR/13,102',
			],
		'br' => [
			'type' => 'cg',
			'sites' => '99',
			'name' => 'Black Rock Campground',
			'features' => 'H,D,W',
			'fee' => '25',
			'coordinates' => '34.0733,-116.3907',
			'facility' => '232473',
			'gridpoints' => 'PSR/4,107',
			],
		'cw' => [
			'type' => 'cg',
			'sites' => '62',
			'name' => 'Cottonwood Campground',
			'features' => 'D,W,',
			'fee' => '25',
			'coordinates' => '33.7485,-115.8211',
			'facility' => '272299',
			'gridpoints' => 'PSR/23,89',
			],
		'ry' => [
			'type' => 'cg',
			'sites' => '31',
			'name' => 'Ryan',
			'features' => '',
			'fee' => '20',
			'coordinates' => '',
			'facility' => '10056207',
			'gridpoints' => '',
			],
		'hv' => [
			'type' => 'cg',
			'sites' => 50,
			'name' => 'Hidden Valley Campground',
			'features' => '',
			'fee' => '15',
			'coordinates' => '',
			'facility' => '10005775' ,
			'gridpoints' => '',
			],
		'be' => [
			'type' => 'cg',
			'sites' => '18',
			'name' => 'Belle Campground',
			'features' => '',
			'fee' => '15' ,
			'coordinates' => '',
			'facility' => '258941',
			'gridpoints' => '',
			],
		'wt' => [
			'type' => 'cg',
			'sites' => 15,
			'name' => 'White Tank',
			'features' => '',
			'fee' => 15,
			'coordinates' =>'' ,
			'facility' => '10005778',
			'gridpoints' => '',
			],
		'sp' => [
			'type' => 'cg',
			'sites' => 6,
			'name' => 'Sheep  Pass (group)',
			'features' => '',
			'fee' => '50',
			'coordinates' => '',
			'facility' => '232470',
			'gridpoints' => '',
			],
		'icg' => [
			'type' => 'cg',
			'sites' => '',
			'name' => 'Indian Cover Group',
			'features' => '',
			'fee' => '',
			'coordinates' => '',
			'facility' => '10005779',
			'gridpoints' => '',
			],
		];

// used for weather, airquality, tests, etc
	private static $places = [
		'hq' => [
			'type' => 'p',
			'sites' => '',
			'name' => 'Park Headquarters',
			'features' => '',
			'fee' => '',
			'coordinates' => '',
			'facility' => '',
			'gridpoints' => '',
			],
		'29p' => [
			'type' => 'p',
			'sites' => '',
			'name' => 'Twentynine Palms',
			'features' => '',
			'fee' => '',
			'coordinates' => '34.1348,-116.0815',
			'facility' => '',
			'gridpoints' => 'VEF/72,12',
			],
		'pdx' => [
			'type' => 'p',
			'sites' => '',
			'name' => 'Portland ,OR',
			'features' => '',
			'fee' => '',
			'coordinates' => '45.5152,-122.6784',
			'facility' => '',
			'gridpoints' => '',
			],
		'lhrs' => [
			'type' => 'p',
			'sites' => '',
			'name' => 'Lost Horse Ranger Station',
			'features' => '',
			'fee' => '',
			'coordinates' => '34.01779,-116.18857',
			'facility' => '',
			'gridpoints' => '',
			],
		'kv' => [
			'type' => 'p',
			'sites' => '',
			'name' => 'Keys View',
			'features' => '',
			'fee' => '',
			'coordinates' => '33.9272,-116.1875',
			'facility' => '',
			'gridpoints' => 'PSR/11,99',
			],
		'shasta' => [
			'type' => 'p',
			'sites' => '',
			'name' => 'Mt Shasta',
			'features' => '',
			'fee' => '',
			'coordinates' => '41.3099,-122.3106',
			'facility' => '',
			'gridpoints' => '',
			],

		];
	//forecast zones used for alerts.  Weeather uses PSRs
private static $zones = array(
	'jr' => 'CAZ560', #hq, br, cw.
	'cv' => 'CAZ061', #coachewlla valley
	'shasta' => 'CAZ285',
	'pdx' => 'ORZ006',
	'cw'=>'CAZ560',
	'br'=>'CAZ560',
);


private static $rpages = array (
		'today' => 'Today and Tonight, Park conditions',
		'notices' => 'Notices and recommendations',
		'weather' => 'Weather next 2 days',
		'events' => 'Calendar next 2 days',
		'camps' => 'Campgrounds',
		'fees' => 'Fees (condensed)',

	);

	private static $campStatuses = [
		'First',
		'Reserved',
		'Closed',
	];



	public static function getFacility($fcode){
		return self::$facilityCodes[$fcode];
	}
	public static function getGridpoints($loc){
		return self::$gridpoints[$loc] ?? '';
	}
	public static function getCoords($loc) {
		return self::$coordinates[$loc] ?? '';
	}



public static function getFees() {
		return
	json_decode(REPO_PATH . '/config/fees.json',true);
	}

public static function getLocName($site) {
	return self::$locnames[$site] ?? '';
}
public static function getCampStatusCodes() {
	return self::$campStatuses;
}
public static function getCampCodes() {
	return array_keys(self::$campsites);
}
public static function getCampSites($cs) {
	return self::$campsites[$cs];
}
public static function getCampfee($cs) {
	return self::$campfees[$cs];
}
public static function getRemotePageKeys(){
	return array_keys(self::$rpages);
}
public static function getRemotePageName($rp){
	return self::$rpages[$rp] ??'';
}
public static function getRpageArray() {
	return self::$rpages;
}
public static function getZone($loc){
	return self::$zones[$loc];
}

}
