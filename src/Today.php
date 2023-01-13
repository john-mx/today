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
	$this -> wlocs = ['jr','cw','br','hq'] ; // weather locations
	$this -> airlocs = ['jr','cw','br']; // air quality locations
	// get wgov update imte
	$wgov = $this->load_cache('wgov');
	$this->wgovupdate = strtotime($wgov['jr']['properties']['updated']);
	$wapi = $this->load_cache('wapi');
	$this->wapiupdate = $wapi['jr']['current']['last_updated_epoch'];
	$this-> sunset = '';





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


//	Utilities::echor($topics,'topics',STOP);

		return $topics;
}


public function prepare_admin() {
// get sections needed for the admin form


	if (!$admin = $this->load_cache('admin')){
	 	Log::error ("Could not load cache admin");
	 	exit;
	 	return [];
	 }
	 //transfer unmodified field
	 foreach (['pithy','fire_level','announcements',
	 'advice','uncertainty','rdelay','alert_alt',
	 ] as $f){
	 	$y[$f] = $admin[$f];
	 }
// 	Utilities::echor ($y, 'read admin cache', NOSTOP);

	// set firelevel options array
	$fire_levels = array_keys(Defs::$firewarn);
	$y['fire_level_options'] = Utilities::buildOptions($fire_levels,$admin['fire_level']);

// camps
	foreach (array_keys(Defs::$campsites) as $cgcode){
		$opt = Utilities::buildOptions(Defs::$cgstatus, $admin['cgstatus'][$cgcode] ?? '');
		$opts[$cgcode]  = $opt;
		$notes[$cgcode] = $admin['cgnotes'][$cgcode] ?? '';

	}
	$rchecked = [];
	$rotators = $admin['rotate'] ?? [];
	foreach (array_keys(Defs::$rpages) as $pid){
		if (in_array($pid,$rotators)){$rchecked[$pid] = 'checked';}
	}
	$y['cg_options'] = $opts;
	$y['cg_notes'] = $notes;
	$y['rchecked'] = $rchecked;
	$y['cgsites'] = array_merge($this->load_cache('cgopen'), $this->load_cache('cgres'));
	//$y['admin']['cgfull'] =  (!array_filter($y['admin']['cgopen'])) ? 1:0;
	//$y['admin']['cgres'] = $this->load_cache('cgres') ?? [];


	foreach (['alertA'] as $alertID){
		$atitle = trim($admin[$alertID]['title'] ??'');
		$atext = $admin[$alertID]['text']??'';
		$aexp = $admin[$alertID]['expires']??'';
		if (empty ($atitle) || ($aexp<time()) ){
			$btitle=$btext=$bexp='';
		} else {
			$btitle=$atitle;
			$btext=$atext;
			$bexp = date('M d g:i a',$aexp);
		}
		$y[$alertID]['title'] = $btitle;
		$y[$alertID]['text'] = $btext;
		$y[$alertID]['expires'] = $bexp;
	}


	$r['admin'] = $y;

	if (!$r['galerts'] = $this->load_cache('galerts') ){
	 	Log::error ("Could not load cache galerts");
	 	return [];
	 }


// calendar
	$calendar = $this->Cal->filter_calendar($this->load_cache('calendar'),0);

#add 3 blank recordsw
	for ($i=0;$i<3;++$i) {
		$calendar[] = $this->Cal::$empty_cal;
	}

	$calendar = $this->Cal->add_types($calendar);
	$r['calendar'] = $calendar;





//  Utilities::echor ($r, 'r to admin',NOSTOP);
	return $r;
}

public function load_cache ($section,$refresh=true) {
	// normally checks for out of date cacghe and rebuilds it.
	// prevent refresh check by setting refresh - false.
	// This is need to prevent endless loop if rebuild includes another load.

#echo "loading cache $section" . BR;

	// see if already loaded
	if (isset($this->$section)){
		return $this->$section;
	}
	// check validity
		if  (!file_exists (CACHE[$section]) ) {
			Log::info("rebuilding non-existent cache $section.");
			$this->refreshCache($section);
		}

		if ($refresh){

			$ot = $this->over_cache_time($section);
			$limit = Defs::getMaxTime($section);
			$otm = round($ot/60);
			if ($ot>2*$limit){
				Log::notice("Loading $section is stale. $otm minutes");
				//echo ("$section limit $limit ot $ot").BR;
				//$this -> refreshCache($section);
			}
		}

		if (!$y = json_decode ($this->file_get_contents_locking(CACHE[$section]), true)) {
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


public function post_admin ($post) {
 /* insert posted data and dependencies into cacjes


*/
//  Utilities::echor ($post, 'Posted' );

//  admin cache
	$y=[];
	$y['announcements'] = trim($post['announcements']);
	$y['updated'] = date('d M H:i');
	$y['pithy'] = trim(Utilities::despecial($post['pithy']));
//fire

	$y['fire_level'] = $post['fire_level'];
//weather
	$y['alert_alt'] = $post['alert_alt'];

	$y['advice'] = trim($post['advice']);


	$y['cgstatus'] = $post['cgstatus']; // array
// 	Utilities::echor ($y,'to write admin cache',STOP);
	$y['cgnotes']  =$post['cgnotes'] ; //array
	$y['uncertainty'] = $post['uncertainty']; #hours to keep site avail
	$y['rotate'] = $post['rotate']; //array
//Utilities::echor($y,'y',STOP);
	$y['rdelay'] = $post['rdelay']; #rotation time


// check alerts
	foreach (['alertA'] as $alertID) {
		$y[$alertID] = $this->checkAlert($post[$alertID]);
	}
	//Utilities::echor($y ,'post',STOP);

	$this -> write_cache('admin',$y);

	$cgo = $post['cgupdate'];
//	Utilities::echor ($cgo,'cgupdate from post');
	// remove any enbtries with blank avlues
	$cgo = array_filter($cgo,function ($val) {return ($val !== '' );});
	//Utilities::echor ($cgo,'cgupdate after filter');
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

private function checkAlert ($alert) {
//   Utilities::echor($alert,'start alert check');
	if (!$alert || empty($alert['title'])){return [];}
	if (empty (trim($alert['title']))) {
// 		echo "cleared";
		$y['expires'] = $y['text'] = $y['title'] = '';

	} else {
		$y['title'] = $alert['title'];
		$y['text'] =  $alert['text'];
		if (empty($alert['expires'])){

			Utilities::alertBadInput("Must have an expiration date for an alert");
		}
		try{$alertAx = new \DateTime($alert['expires'],new \DateTimeZone('America/Los_Angeles'));}
		catch (\Exception $e) {
			Utilities::alertBadInput ("Cannot understand date/time: {$alert['expires']}");
		}
		$alertAxts = $alertAx->format('U');
		if ($alertAxts < time()) {
			Utilities::alertBadInput("Expiration less than now.  To delete item, remove the title");
		}

		$y['expires'] = $alertAxts;
	}
//  Utilities::echor($y,'checked alert');
	return $y;
}


public function mergeCache($cache,$merge){
// merges data into cache, unless data is empty
		$x = $this->load_cache($cache,false) ; #don't refresh it	Log::info("Merged updated cache $loginfo");
//		Utilities::echor ($x, "merge: Loaded cache $cache");
//		Utilities::echor ($merge,'merge: Data to merge');
		if (! empty ($merge)){ #if empty you're done
			$y = array_merge($x,$merge);
// 		Utilities::echor ($y,'merged cache');
			$this->write_cache($cache,$y);
		}
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
	if (!$z=$this->load_cache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }

#	Utilities::echor($z,'calendar',STOP);
//	$y=$this->Cal->filter_calendar($z,0);
	return ['calendar' => $z];
}
public function build_topic_current() {
	if (!$z=$this->load_cache('current',false)) {
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

	$wapi = $this->load_cache('wapi');
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
public function build_admin_calendar() {
	if (!$z=$this->load_cache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }

#	Utilities::echor($z,'calendar',STOP);
	$y=$this->Cal->filter_calendar($z,0);
	return ['calendar' => $y];
}

public function build_topic_uv() {
	$y = $this->load_cache('wapi');
//Utilities::echor ($y);
	$uv = $y['jr']['forecast']['forecastday'][0]['day']['uv'];
	$uvdata=$this->uv_data($uv);
	return ['uvdata'=>$uvdata];
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

// prepare data for the ight displlay:
// needs astro, today high/low wind
// must accomodate failed wapi or wgov

public function build_topic_fees() {
	$fees = Defs::getFees();
	return ['fees'=>$fees];
}
public function build_topic_light() {
	$z = array(
	'day'=>array(
		'sunrise'=>'unknown',
		'sunset' => 'unknown',
		'wind' => 'unknown',
		'high' => 'unknown',
		'short' => 'unknown',
		'icon' => 'unknown',
		'uv' => 'unknown',

		),

	'night'=>array(
		'moonrise'=>'unknown',
		'moonset'=> 'unknown',
		'wind' => 'unknown',
		'low' => 'unknown',
		'short' => 'unknown',
		'icon' => 'unknown',
		'moonphase' => 'unknown',
		'moonillum' => 'unknown',

		),
	'update' => array(
		'ts' => time(),
		'source' => 'unknown',
		),
	);
	//Utilities::echor($z,'z init',STOP);

		// count how many periods captured.  If if it ends up 1, then
		// there is only a night component.  If it's 2, then there
		// is both day and night.
		// If only nighgt, then day info is left over from wapi, and is
		// for 29p, not jumbo rocks.

		$period_count = 0;

	if ($wapi =  $this->load_cache('wapi') ){

		$daily = $wapi['br']['forecast']['forecastday'][0];
		$astro = $daily['astro'];
		$high = round($daily['day']['maxtemp_f']) . "&deg;F ("
			. round($daily['day']['maxtemp_c']) . "&deg;C)";
		$low = round($daily['day']['mintemp_f']) . "&deg;F ("
			. round($daily['day']['mintemp_c']) . "&deg;C)";
		$wind = 'To ' . round($daily['day']['maxwind_mph']) . 'mph' ;


		//$wapi = $this->format_wapi($y);
		//Utilities::echor($wapi,'formatted wapi', NOSTOP);
		$z['day']['sunrise'] =  $this -> time_format( $astro['sunrise']);
		$z['day']['sunset'] = $this -> time_format($astro['sunset']);
		$z['day']['wind'] = $wind;
		$z['day']['high'] = $high;
		$z['day']['short'] = $daily['day']['condition']['text'];
		$z['day']['icon'] = $daily['day']['condition']['icon'];
		$z['day']['uv'] = $daily['day']['uv'] ;
		$z['day']['period_count'] = $period_count;

		$z['night']['moonrise'] =  $this -> time_format($astro['moonrise']);
		$z['night']['moonset'] = $this -> time_format($astro['moonset']);
		$z['night']['wind'] = $wind;
		$z['night']['low'] = $low;
		$z['night']['icon'] = Defs::getMoonPic($astro['moon_phase']);
		$z['night']['moonphase'] =  $astro['moon_phase'];
		$z['night']['moonillum'] = $astro['moon_illumination'];
		$z['night']['period_count'] = $period_count;

		$tomorrow = $wapi['br']['forecast']['forecastday'][1];
		$astro = $tomorrow['astro'];
		$high = round($tomorrow['day']['maxtemp_f']) . "&deg;F ("
			. round($tomorrow['day']['maxtemp_c']) . "&deg;C)";
		$low = round($tomorrow['day']['mintemp_f']) . "&deg;F ("
			. round($tomorrow['day']['mintemp_c']) . "&deg;C)";
		$wind = 'To ' . round($tomorrow['day']['maxwind_mph']) . 'mph' ;
		$z['tomorrow']['sunrise'] =  $this -> time_format( $astro['sunrise']);
		$z['tomorrow']['sunset'] = $this -> time_format($astro['sunset']);
		$z['tomorrow']['wind'] = $wind;
		$z['tomorrow']['high'] = $high;
		$z['tomorrow']['short'] = $tomorrow['day']['condition']['text'];
		$z['tomorrow']['icon'] = $tomorrow['day']['condition']['icon'];
		$z['tomorrow']['uv'] = $tomorrow['day']['uv'] ;
		$z['tomorrow']['period_count'] = $period_count;

		$z['update']['ts'] = $wapi['br']['current']['last_updated_epoch'];
		$z['update']['source'] = 'Forecast for Black Rock from weatherapi.com';
		$z['update']['period_count'] = $period_count;

		//Utilities::echor($z,'z init',STOP);
		$this->sunset = $z['day']['sunset'] ?? '';
	}


	if (//0 &&  //uncomment to simulate wgov failure
		(time() - $this->getMtime('wgov') < 12*60*60 ) #< 12 hours old
		&& ($wgov = $this->load_cache('wgov') )
		&& (isset($wgov['jr']['properties']['updated'] ))
		){
		// update for wgov data
		$tomorrow_done = false;
//Utilities::echor($wgov,'wgov from cache');
		$wupdated = strtotime($wgov['jr']['properties']['updated']) ;
		$period_count = 0;

		foreach ($wgov['jr']['properties']['periods'] as $period) {
			if (strtotime($period['endTime']) < time()){ #already ended
				//echo "Skipping period ${period['name']} ".BR;
				continue;
			}
			//if ($period['isDaytime']) continue; // test no night segment
			++$period_count;
			//Utilities::echor($period,'period ');
			// found first period that has not ended yet

			if ($period['isDaytime'] && $period_count == 1) {
				$periodName = 'day';
				// $wind = $period['windSpeed'] . ' ' . $period['windDirection'];
				$wind = $period['windSpeed'];
				$temperature = $period['temperature'];
				$tempc = round(($temperature -32 )* 5/9,0);
				$high =  "$temperature&deg;F ($tempc&deg;C)";
				//$z['day']['sunrise'] =  $this -> time_format( $astro['sunrise']);
				//$z['day']['sunset'] = $this -> time_format($astro['sunset']);
				$z[$periodName]['wind'] = $wind;
				$z[$periodName]['high'] = $high;
				$z[$periodName]['short'] = $period['shortForecast'];
				$icon = $period['icon'];
				$z[$periodName]['endTimets'] = strtotime($period['endTime']);

				//$z['day']['uv'] = $daily['day']['uv'] ;
				continue;
			} elseif (!$period['isDaytime'])  {#got night time
				$periodName = 'night';

				$temperature = $period['temperature'];
				$tempc = round(($temperature -32 )* 5/9,0);
				$low = "$temperature &deg;F ($tempc &deg;C)";
				// $wind = $period['windSpeed'] . ' ' . $period['windDirection'];
				$wind = $period['windSpeed'];

				//$z['night']['moonrise'] =  $this -> time_format($astro['moonrise']);
				//$z['night']['moonset'] = $this -> time_format($astro['moonset']);
				$z[$periodName]['wind'] = $wind;
				$z[$periodName]['low'] = $low;
				$z[$periodName]['icon'] = Defs::getMoonPic($astro['moon_phase']);
				$short = $period['shortForecast'];
				//$z[$periodName]['moonphase'] =  $astro['moon_phase'];
				//$z[$periodName]['moonillum'] = $astro['moon_illumination'];
			// night period always exists if wgov is successfull,
			// so set updates there.
				$z[$periodName]['period_count'] = $period_count;
				$z[$periodName]['endTimets'] = strtotime($period['endTime']);
				$z['update']['ts'] = $wupdated;
				$z['update']['source'] = 'Forecast for Jumbo Rocks from weather.gov';


			} #end night
			elseif ($period['isDaytime'] && $period_count > 1) { // tomorrow
				$temperature = $period['temperature'];
				$tempc = round(($temperature -32 )* 5/9,0);
				$high = "$temperature&deg;F ($tempc&deg;C)";
				// $wind = $period['windSpeed'] . ' ' . $period['windDirection'];
				$wind = $period['windSpeed'];

				//$z['night']['moonrise'] =  $this -> time_format($astro['moonrise']);
				//$z['night']['moonset'] = $this -> time_format($astro['moonset']);
				$z['tomorrow']['wind'] = $wind;
				$z['tomorrow']['high'] = $high;
				$z['tomorrow']['icon'] = $icon;
				$z['tomorrow']['short'] = $period['shortForecast'];
				$z['tomorrow']['endTimets'] = strtotime($period['endTime']);
				$tomorrow_done = true;


		}
				$z[$periodName]['wind'] = $wind;
				$z[$periodName]['low'] = $low;
				$z[$periodName]['icon'] = Defs::getMoonPic($astro['moon_phase']);
				$z[$periodName]['short'] = $short;
				$z[$periodName]['period_count'] = $period_count;
				$z[$periodName]['endTimets'] = strtotime($period['endTime']);



		if ($tomorrow_done) break; // stop looking
		} #end foreach

	} #end wgov
 	//Utilities::echor($z,'light prepared',STOP);

	return ['light' => $z];
} #end function


public function build_topic_weather() {
	if (!$wgov = $this->load_cache('wgov') ){
	 	Log::error ("Could not load cache wgov");
	 	echo "No wgov";
	 	return [];
	 }

	$z['wgov'] = $this->format_wgov($wgov);
// Utilities::echor($z,'formatted wgov',STOP);

	//get current temp
	$w = $this->load_cache('wapi');
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

// admin cache contains status and notes for each cg
	if (!$y = $this->load_cache('admin') ){
	 	Log::error ("Could not load cache admin");
	 	return [];
	 }
//Utilities::echor($y, 'loaded admin cache');

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

	if (!$y = $this->load_cache('admin') ){
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

			$z['version'] = $this->file_get_contents_locking(REPO_PATH . "/data/version") ;
			$z['target'] = date('l F j, Y');

			$z['advice'] = Utilities::special($y['advice']);
			$z['rotate'] = $y['rotate'] ?? [];
			$z['rdelay'] = $y['rdelay'] ?? [];

// 	Utilities::echor($z,'topic general');
	return ['admin'=>$z];

}


########  CACHES #############
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

public function refresh_caches($force=false) {
	Log::info ("Starting all cache refresh cycle");

/* refreshes all the external caches, if they are due

*/

	if ($this->over_cache_time('wapi') > 0 || $force) {
			$this->rebuild_cache_wapi();
			echo "wapi done" . BR;

	}
	if ($this->over_cache_time('airq') > 0|| $force) {
		$this->rebuild_cache_airq();
			echo "airq done" . BR;
	}
	if ($this->over_cache_time('airowm')> 0 || $force) {
		Log::error("airowm updating because " . $this->over_cache_time('airowm') );
		$this->rebuild_cache_airowm();
		echo "airown done" . BR;

	}
	if ($this->over_cache_time('wgov')> 0 || $force || 0) {
			$this->rebuild_cache_wgov();
			echo "wgov done" . BR;
	}
	if ($this->over_cache_time('airnow')> 0 || $force) {
			$this->rebuild_cache_airnow();
			echo "airnow done" . BR;
	}
	if ($this->over_cache_time('galerts')> 0 || $force) {
			$this->rebuild_cache_galerts();
			echo "galerts done" . BR;
	}
	if ($this->over_cache_time('current') > 0 || $force) {
		$this->rebuild_cache_current();
		echo "current done" . BR;
	}
	if ($this->over_cache_time('calendar') > 0 || $force) {
		$this->rebuild_cache_calendar(); #filter out old stuff
		echo "calendar done" . BR;
	}

	if ($this->over_cache_time('cgres') > 0 || $force) {
		$this->rebuildCGres(); #filter out old stuff
		echo "cgres done" . BR;
	}



	#	$this -> rebuild_properties('jr');
	Log::info ("Completed cache refresh cycle");

}

private function refreshCache($cache) {
	// refresh individual cache on demand.
	/* back and forth over refresh all via cron
	vs refrewsh on deman when loaded.  Problem with
	doing it on demand is that it may tie up script
	for a while due to sleep cycles for failed curl
	attempts.  Better to run refresgh as a background
	job by cron.  So use 'refresh_caches' instead of this

	*/
	$ot = $this->over_cache_time($cache);
	switch ($cache) {
		case 'wapi':
			$this->rebuild_cache_wapi();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			break;

		case 'airq':
			$this->rebuild_cache_airq();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");

			break;

		case 'airowm':
			$this->rebuild_cache_airowm();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			break;

		case 'wgov':
			$this->rebuild_cache_wgov();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			break;

		case 'airnow':
			$this->rebuild_cache_airnow();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			break;

		case 'galerts':
			$this->rebuild_cache_galerts();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			break;

		case 'current':
			$this->rebuild_cache_current();
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			break;

		case 'calendar':
			$this->rebuild_cache_calendar(); #filter out old stuff
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			break;

		case 'cgres':
			$this->rebuildCGres(); #rebuild from rec.gov
			Log::info ("Refreshed cache $cache. Overtime = $ot.");
			break;

		default:
			die ("Attempt to refresh unknown cache $cache");

	}

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

	$this->write_cache($src,$x);
	Log::info("Saved updated cache $src");
	$this->wapiupdate = $x['jr']['current']['last_updated_epoch'];
	return $x;
}

private function rebuild_cache_calendar () {
	$x = $this->load_cache('calendar',false);
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

		$url = "http://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid=" . Defs::getKey('openweathermap');

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
		//Utilities::echor($aresp,'current aresp');
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

		$url = "https://www.airnowapi.org/aq/observation/latLong/current/?format=application/json&latitude=$lat&longitude=$lon&distance=25&API_KEY=" . Defs::getKey('airnow');
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

	 if (!$r = $this->load_cache('wapi',false) ) {
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
		$this->write_cache('galerts',$y);
//Utilities::echor($y,'from external  alerts', NOSTOP);
	return $y;
}

public function rebuildCGres() {
	/* rebuild the cgres cache
	*/

		// for now just:
		touch (CACHE['cgres']);


		// if (! 1 #||  load rec.gov data
// 		){
// 			Log::info("Cannot retrieve opens from rec.gov");
// 			// do leave for another time.
// 			return false;
// 		}
		// update opens from fretried data
		//$this->write_cache('cgres',$current_res);
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

	//$this->write_cache($src,$x);
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
	//Utilities::echor($r,'From external',STOP);

	foreach ($r as $loc => $d){	//uses weather.gov api directly
		$y[$loc] = $d['properties'];
	} #end foreach
// Utilities::echor($y,'properties',STOP);

	$this->write_cache('properties',$y);
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
		XXX Returns true if time is within 5 minutes of limit
	*/
	if (!file_exists(CACHE[$section])){ die ("No cache file for $section");}
	$limit = Defs::getMaxTime($section) ; #in seconds
	if (!$limit){ return 0;}

	$filetime = filemtime (CACHE[$section]);
	$age = time() - $filetime;
	$margin = 0;
	if ( $age > ($limit - $margin) ) return $age; #in seconds

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
				Utilities::echoAlert ($e->getMessage());
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

function get_external ($loginfo, $url,string $expected='',array $header=[]) {
		/* tries to geet the url, tests for suc cess and
			for expected result if supplied.
			returns result array on success
			returns false on erro.
			$loginfo is just for Log info
		*/
		$curl = curl_init();
		$curl_options = $this->curl_options();
		curl_setopt_array($curl,$curl_options);
		curl_setopt($curl,CURLOPT_URL, $url);
		if ($header)
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		$aresp = [];
		$success=0;
		$fail = '';
		$tries = 0;

		while (!$success) {
			//Utilities::echor($aresp,"Here's what I got for $loginfo:");
			if ($tries > 2){
					//echo "Can't get valid data from ext source  $loginfo";
				Log::notice("External failed for $loginfo: $fail.",$aresp);
				return [];
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
					sleep (5);

			} else {
				curl_close($curl);
				Log::info ("External succeeded for $loginfo.  Tries $tries.");
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
