<?php
namespace DigitalMx\jotr;


use DigitalMx\jotr\Log;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;




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


###############################

public function __construct($c){
	$this->Plates = $c['Plates'];
	$this -> Defs = $c['Defs'];
	$this-> CM = $c['CacheManager'];


	$this->Cal = $c['Calendar'];
	$this->Camps = $c['Camps'];
	// locations to use for weather report
	$this -> wlocs = ['jr','cw','br','hq'] ; // weather locations
	$this -> airlocs = ['jr','cw','br']; // air quality locations
	$this->light = $this->build_topic_light()['light'];
	$this->sunset = $this->light['Today']['sunset'];


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

		);


		$this->Plates->addData($topics,
[
	'today','light','notices','conditions','advice','weather',
	'campground', 'summary', 'condensed','campground-tv',
	'alerts','summary','weather','weather-wapi','weather-wgov',
	'weather-one-line','weather-tv','weather-wgov-tv','calendar',

]);


		return $topics;
}










public function buildPDF(){
	$y = $this->prepare_topics ();
//Utilities::echor($y,'y',STOP);

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
	if (!$z=$this->CM->loadCache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }

#	Utilities::echor($z,'calendar',STOP);
//	$y=$this->Cal->filter_calendar($z,0);
	return ['calendar' => $z];
}
public function build_topic_current() {
	if (!$z=$this->CM->loadCache('current',false)) {
	 	Log::error ("Could not load cache current");
	 	return [];
	 }

	$y=$z['lhrs']['properties'];
	$y['updatets'] = strtotime($z['lhrs']['properties']['timestamp']);
	$y['temp_c']= is_null($y['temperature']['value']) ? 'n/a' : round($y['temperature']['value']);
	$y['temp_f'] = is_null($y['temperature']['value']) ? 'n/a' :round( ($y['temp_c'] * 9/5) + 32);
	$y['wind_kph'] = is_null($y['windSpeed']['value'])? 'n/a':round($y['windSpeed']['value']);
	$y['wind_mph'] =  is_null($y['windSpeed']['value'])? 'n/a':round($y['wind_kph'] /2.2) ;
	$y['wind_direction'] = $this->degToDir($y['windDirection']['value']??0);

	$wapi = $this->CM->loadCache('wapi');
	$current_uv = $wapi['jr']['current']['uv'];
	$y['uv'] = $this->uv_data($current_uv);

// Utilities::echor($y,'current', NOSTOP);
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
	$y = $this->CM->loadCache('wapi');
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

	return ['air' => $y];
U::echor($y,'air',STOP);
}

// prepare data for the ight displlay:
// needs astro, today high/low wind
// must accomodate failed wapi or wgov

public function build_topic_fees() {
	$fees = Defs::getFees();
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
		&& ($wapi = $this->CM->loadCache('wapi') )
		){

		// build light data from wapi (using black rock data)
		$period_count = 0;
		$today_done = false;
		foreach ($wapi['br']['forecast']['forecastday'] as $day) {
		// make sure it is not passed
			//echo "On ${day['date']} , ts ${day['date_epoch']} , date ${day['date']}" .BR;
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

	if (1   //uncomment to simulate wgov failure
		&& ($wgov = $this->CM->loadCache('wgov') )
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
				//echo "Skipping period ${period['name']} ".BR;
				continue;
			}
			//if ($period['isDaytime']) continue; // test no night segment
			++$period_count;
			//Utilities::echor($period,'period ');
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
				$z[$periodName]['icon'] = Defs::getMoonPic($astro['moon_phase']);
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
	if (!$wgov = $this->CM->loadCache('wgov') ){
	 	Log::error ("Could not load cache wgov");
	 	echo "No wgov";
	 	return [];
	 }

	$z['wgov'] = $this->format_wgov($wgov);
// Utilities::echor($z,'formatted wgov',STOP);

	//get current temp
	$w = $this->CM->loadCache('wapi');
	$z['wapi'] = $this->format_wapi($w);

//	$z['wgov_forecast'] = $this->format_wgov_forecast($wgov);


//	Utilities::echor($z,'weather topic');
	return $z;
}

public function build_topic_campgrounds() {
/* reads the res and open caches for available
	sites at each campgrund.  Prepares a display
	for each site based on campground status
	(.e., closed) and age of cache (changes to
	'?' if cache is too old.
*/
return [];
// admin cache contains status and notes for each cg
	if (!$camps = $this->CM->loadCache('camps') ){
	 	Log::error ("Could not load cache camps");
	 	return [];
	 }
//Utilities::echor($y, 'loaded admin cache');

	$w['cg_notes'] = $y['cgnotes'];
	$w['camps']['cg_status'] = $y['cgstatus'];
	$w['camps']['cgfull'] = $cgfull = $y['cgfull'] ?? false;


// get age of each cache.
// 	$cgopen_age = $this->getMtime('cgopen');
// 	$cgres_age = $this->getMtime('cgres');
// 	$w['camps']['cgopen_age'] = $cgopen_age;
// 	 $w['camps']['cgres_age'] = $cgres_age;
// 	//$cg_uncertain = 0; // hours until display changes to ? (0 disables test)
// 	$uncertainty = $y['uncertainty'] ?? 0;

	// // load the two caches and set display
// 	if (! $cgsites = array_merge($this->CM->loadCache('cgopen'),$this->CM->loadCache('cgres'))){
// 	 	Log::error ("Could not load cache cgopen or cgres");
// 	 	die();
// 	 }
// 	//set display based on status and age
// 	 foreach ($cgsites as $cg=>$open){ // ic => 7
// 	 	$status = $w['camps']['cg_status'][$cg];
// 		$display = $this->getCgDisplay($status,$open,$cgfull,$cgopen_age,$cgres_age,$uncertainty);
// 		$w['camps']['sites'][$cg] = $display;
// 	}
//


//Utilities::echor($w['camps'], 'camps', NOSTOP);
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

	if (!$y = $this->CM->loadCache('admin') ){
	 	Log::error ("Could not load cache admin");
	 	return [];
	 }
// 	Utilities::echor($y , 'loaded cache admin');
		//clean text for display (spec chars, nl2br) but don't change stored info.

		 	$t = $this->clean_text($y['pithy']);
			 $z['pithy'] = $t;

			$z['notices']['alerts'] = [];
			foreach (['alertA'] as $alertID){
				$atext =  $this->format_alerts($y[$alertID]);
				//Utilities::echot ($atext,'alert',STOP);
				if ($atext){$z['notices']['alerts'][] = $atext;}
			}

			$z['alert_alt'] = $y['alert_alt'] ?? '';

			$t = $this->clean_text($y['announcements']);
			$z['notices']['announcements'] = trim($t);
			//Utilities::echor ($z['notices'],'build topic general',STOP);

			$fire_level = $y['fire_level'];
			$z['fire']['level'] = $fire_level;
			$z['fire']['color'] = Defs::get_firecolor($fire_level);

			$z['version'] = file_get_contents(REPO_PATH . "/data/version") ;
			$z['target'] = date('l F j, Y');

			$z['advice'] = Utilities::special($y['advice']);
			$z['rotate'] = $y['rotate'] ?? [];
			$z['rdelay'] = $y['rdelay'] ?? [];

// 	Utilities::echor($z,'topic general');
	return ['admin'=>$z];

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
			$y['airwarn'] = Defs::getAirWarn($aqi_scale);

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

public function display_weather(array $wslocs=['jr','br','cw'],int $wsdays=3 ) {

//Utilities::echor($wgov,'weather',STOP);

	$wspec = array('wslocs'=>$wslocs,'wsdays'=>$wsdays);

	if(1
	&& isset($this->wgovupdate)
	&& ($wgovupdate = $this->wgovupdate )
	&&( (time() - $wgovupdate) < 8*60*60)
	) {#use wgov

		echo $this->Plates->render('weather-wgov',$wspec);
	}elseif (1 #use wapi
	&& isset($this->wapiupdate)
	&& ($wapiupdate = $this->wapiupdate )
	&&( (time() - $wapiupdate) < 8*60*60)
	) {
		echo $this->Plates->render('weather-wapi',$wspec);
	} else { #no good datea
		echo "Cannot build weather data.  All forecasts are stale.";
	}

}
private function format_alerts($alert){
	if (empty($alert)) return '';
	if (empty($alert['title']) or ($alert['expires'] < time() )) return '';
	$expire_date = date('M d g:i a',$alert['expires']);

	$t= "<div class='warnblock'>";
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
			$dayts = strtotime($start);
			$daytext = date('l, M d',$dayts);
			if ($daytext != $lastday){
				++$day;
				$lastday = $daytext;
			}
			if ($day > 3) break;
			$highlow = $perdata['isDaytime']? 'High':'Low';
			$daynight= $perdata['isDaytime']? 'Day':'Night';
			$tempc = round(($perdata['temperature'] -32 )* 5/9,0);
			$perdata['dayts'] = $dayts;
			$perdata['daytext'] = $daytext;
			$perdata['highlow'] = $highlow . "&nbsp;" . $perdata['temperature'] . "&deg;F (" .$tempc . "&deg;C)" ;

			$x[$loc][$day][$daynight] = $perdata;

		} #end foreach period
	} #end foreach location

//	Utilities::echor($x,"formatted wgov", STOP);
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



public  function uv_data($uv) {
	// takes numeric uv, returns array of uv, name, warning
		$uvscale =  Defs::uv_scale($uv);
		$uv = array(
			'uv' => $uv,
			'uvscale' => $uvscale,
			'uvwarn' => $this ->Defs->uv_warn($uvscale),
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

####################OBSOLETE ###########################


} #end class
