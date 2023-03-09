<?php
namespace DigitalMx\jotr;

//BEGIN START
use DigitalMx\jotr\Utilities as U;





//END START


class Calendar {

	 static $empty_cal = array (
	 	'date'=>'',
	 	'days'=> '',
	 	'end'=> '',
	 	'time'=>0,
	 	'location'=>'',
	 	'type'=>'',
	 	'title'=>'',
	 	'duration' => '',
	 	'reservation' => false,
	 	'note'=>'',
	 	'status' =>'new',
	 	'canceldate' => '',
	 	'suspenddate' => '',
	 	'dt' => 3, // for sorting on admin screen

	 	);

	 static $eventtypes = array(
	'Astronomy Program',
	'Childrens Program',
	'Cultural Program',
	'Evening Program',
	'Guided Activity',
	'Guided Tour',
	'Partner Program',
	'Ranger Chat',
	'Ranger-led Hike',
	'Social Event',

	);

	private $tz;
	private $CM;
	private $Plates;

	public function __construct($c) {
		$this->tz = new \DateTimezone('America/Los_Angeles');
		$this->CM = $c['CacheManager'];
		$this->Plates = $c['Plates'];
	}

	public function dayset(int $i,string $days) {
		// i is the calendar line
		// days is a string of '' or up to 7 digits, for day nuymbers 0..6
		$t='';
		for ($j=0;$j<7;++$j) {

			$daychecked = strpos($days,strval($j)) !== false ?'checked':'';
			#echo "checking for $j in $days $daychecked" . BR;
			$t .= "<input type='checkbox' name='calendar[$i][day$j]' $daychecked> ";
		}
		return $t;
	}


public function add_types ($events) {
	// adds type select options to each entry
// 	U::echor($events,'in add type');
	$z=[];
	foreach ($events as $event){
// U::echor($event);
		$event['typeoptions']  = Utilities::buildOptions(self::$eventtypes,$event['type']);
		$z[] = $event;
	}

return $z;
}

public function prepare_admin_calendar() {
	if (!$cal=$this->CM->loadCache('calendar')) {
	 	die ("Could not load cache calendar");
	 }
//  U::echor($cal,'loaded');

	$new_events = [self::$empty_cal];
	$new_events = $this->add_types($new_events);

	$events =$this->filterAdmin($cal['events']);
// 	U::echor($events,'filtered events');
	// addd 3 blank records


	$events = $this->add_types($events);


	if (!$npscal=$this->CM->loadCache('npscal')['npscal']) {
	 	Log::error ("Could not load cache npscal");
	 	$npscal = [];
	 }

	// merge
	$events = array_merge($new_events,$events,$npscal);
		//U::echor($events,'merged');
	$cal['events'] =  $events;
	$cal['npstags'] = $this->check_npstags($cal['npstags']);

//  U::echor($cal,'prepared admin');
	return  $cal;
}

private function filterAdmin($events){
	// remove expired events
	$r = [];
	foreach ($events as $event){
		if(!empty($event['canceldate'])) {
			$cancelts = strtotime($event['canceldate']);
			if ($cancelts < time()){ // past
				$event['canceldate'] = '';
			}
		}

		if(!empty($event['suspenddate'])) {
			$suspendts = strtotime($event['suspenddate']);
			if ($suspendts < time()){ // past
				$event['suspenddate'] = '';
			}
		}

		$r[]=$event;
	}

	return $r;
}

public function post_calendar($cal){
// U::echor($cal,'pre check');
	$cal['events'] = $this->check_events($cal['events']);
// U::echor($cal,'after check');// U::echor ($cal ,'to write');
	$cal['npstags'] = self::check_npstags($cal['npstags']);
//  U::echor($cal,'calpost',);
	$this->write_calendar($cal);
	return true;
}


public static function filter_events(array $events,int $transform = 0) {
	/*
		expands all the events into individual events, then filters by date

		Recurring events have days (1..7, where 1 is Sundays)

	Transform is number of days of recurring or scheduled to transform
		into individual dates.  If transform is 0, the recurring
		events are left as is (as for admin screen).  If it's 3,
		then all events are filtered in the next 3 days are inserted as scheduled events.  (as for display).

	*/

if ($transform == 0){
				return $events;
		}

	$z=[];
	//echo "Transform $transform" . BR;

	$nowdt = new \DateTime();
	$nowts = $nowdt -> format('U');

	$todaydt = new \DateTime('today');
	$todayts = $todaydt->format('U');
	$enddt = $todaydt;
	$enddt->modify("+ $transform day");
	$endts = $enddt-> format('U');


	foreach ($events as $event){ #keep if these conditions:
		// expand events in 3 classes: scheduled, repeating, npscal

		// expand nps events
		if (!empty($event['npsid'])) { // from nps calendar - already limited to 7days
			foreach ($event['dates'] as $edate){
				foreach ($event['times'] as $etime) {
					$ets = strtotime("$edate $etime");
					$event['dt'] = $ets;
					$z[] = $event;
				}
			}
//		U::echor($z,'nps events expanced');

 //local scheduled event
		} elseif (empty ($event['dayset']) && !empty ($event['date'] )) {
			$edt = $event['date'] . ' ' . $event['time'];
			$event['dt'] = strtotime($edt);
			$z[] = $event;
			//echo "added scheduled {$event['title']}" . BR;

	// expand local recurring events
		} elseif (!empty ($event['dayset'])) {
			$recurring = self::parse_recurring($event,$todayts, $endts);
			$z = array_merge($z,$recurring);
		} else {
			Log::error ("Event does not fit any types for filter",$event);
			die ("Something very wrong  with this event:<br />"
				. 'title: ' . $event['title'] . BR
				. 'date: ' . $event['date']
				);
		}
	}  // end foreach.  Now have list of individaul events
// U::echor($z, 'pre-windows cal', false);
	$nz=[];
	$event = [];
	foreach ($z as $event){
// 		U::echor ( $event['title'] . ' ' .$event['dt'] . ' ' . $nowts .' '. $endts);
		if (self::inWindow($event['dt'],$nowts,$endts)) {
			$event['status'] = self::setStatus($event);
//			U::echor($event);
			$nz[] = $event;
		}
	}
	$z=$nz;

	$z = Utilities::element_sort($z, 'dt');
 //	U::echor($z, 'post filter');
	return $z;
}


private static function parse_recurring($event,$todayts,$endts) {
// returns an array of events in time window
// endts is timestamp for end of filter window
		$rlist = [];
		#create actual event list from recurring instructions

		######
		// prepare starting date
		$begindate = $event['date'] ?: date('M d, Y');
		$begindate .= ' ' . $event['time'] ;

		$beginrepeat = new \DateTime($begindate);

		if (!empty($enddate = $event['end'])) $endts = strtotime($enddate);
		// otherwise use $endts in invocation
		$endrepeat = new \DateTime();
		$endrepeat->setTimestamp($endts);

		// may need +1 day??

		$testdt = $beginrepeat;

		##LOOP##
		while ($testdt <= $endrepeat) {
			$wd = $testdt->format('w'); #get day of werek
#echo "... iteration $i " . ' on ' . $testdt->format('M d') . " day is $wd." . BR;

			if (key_exists ($wd,$event['dayset']) ){
				$schevent = $event;
#echo "Set time {$event['time']} to $hrs, $mins. " . BR;
				$schevent['date'] = $testdt->format('M d Y');

	//	echo "Test dt " .$testdt->format('M d Y H:i') . 'for' . $event['time']  . BR;
				$schevent['dt'] = $testdt->format('U');
// 			echo "added recurring {$event['title']} on $testdate." . BR;

// 				echo $schevent['title'] .' '. $schevent['date'] .' '. date('H:i',$schevent['dt']) . BR;

				$rlist[] = $schevent;
			}
			$testdt->modify (" + 1 day");
		}
	//U::echor($rlist,'rlist',STOP);
	return $rlist;
}



public static	function parse_npscal ($edatetime,$event,$endts) {
 //	echo "parsing $edatetime" . BR;
		$edt = strtotime($edatetime);
		$tdate = strtotime('+ ' . $transform . ' day') ;
		$now = time();
		if ($edt < $now){
			//echo "expired $edate; ";
			return [];
		} #past date

		if ($transform && ($edt >= $tdate)) return [];
			//late $edate; ";
		$event['dt'] = $edt;
		//
// 	U::echor ($event,'parse npscal');
		return $event;

}

public static function inWindow($tdate,$nowts,$endts) {
	// all are timestamps
// 	echo __LINE__ . ' test' . date (' n/j  g:i',$tdate) . BR;
// 	echo __LINE__ . ' now' . date ('  n/j  g:i',$nowts) . BR;
// 	echo __LINE__ . ' end' . date ('  n/j  g:i',$endts) . BR;
	if ( ($tdate >= $nowts) && ($tdate <= $endts)) {
		//echo 'hit'.BR.BR;
		return true;
	}
	return false;
}

private static function setStatus ($event) {
//U::echor($event,' event');

if (empty($event['title'])) {echo "Bad event" . BR; return;}
// 	echo ">>> Status on " . $event['title'];
	if( !empty($event['cancelts']) && ($event['dt'] < $event['cancelts'] )) { #cancelled
		$status= 'Cancelled' ;

	}
	elseif  ( !empty($event['suspendts']) && ($event['dt'] < $event['suspendts'] )) {
		$status= 'Suspended';
	}
	else {
		$status = 'OK';
	}

	return $status;
}



public function check_npstags(array $tags) {
// 	U::echor($tags);
	$z=[];
	foreach ($tags as $npsid=>$tag){

		if (!empty($canceldate = trim($tag['canceldate']))) {

			try {$canceldt = new \DateTime($canceldate);}
			catch (\Exception $e){	Utilities::alertBadInput ("Illegal Cancel date for event: $canceldate");
			}
			$cancelts = $canceldt->format('U');
			if ($cancelts > time()){
				$tag['cancelts'] = $cancelts;
				$tag['canceldate'] = $canceldt->format('n/j g:i a');
			} else {
				$tag['cancelts'] = $tag['canceldate'] = '';
			}
		}

		if (!empty($suspenddate = trim($tag['suspenddate']))) {

			try {$suspenddt = new \DateTime($suspenddate);}
			catch (\Exception $e){	Utilities::alertBadInput ("Illegal Suspend date for event: $suspenddate");
			}
			$suspendts =  $suspenddt->format('U');
			if ($suspendts > time()){
				$tag['suspendts'] = $suspendts;
				$tag['suspenddate'] = $suspenddt->format('n/j g:i a');
			} else {
				$tag['suspendts'] = $tag['suspenddate'] = '';
			}
		}


		$z[$npsid] = $tag;

	}

	return $z;
}


public function check_events(array $events) {
// check post data
//	U::echor ($events,'check events');
	$z=[];

	foreach ($events as $event){
		$ze=[]; // modified event
		//U::echor($event,'event' );
		if (empty($time = $event['time'])){continue;}
		if (isset($event['delete'])) continue;

		$ze['time'] = U::format_time($time);


		if (!empty($startdate = trim($event['date']))) {
			// if empty, then today will be used for start
			try {$startdt = new \DateTime($startdate);}
			catch (\Exception $e){	Utilities::alertBadInput ("Illegal Start date for event: $startdate");
			}
			$ze['startts'] = $startdt->format('U');
			$ze['date'] = $startdt->format('n/j/y');
		} else {
			$ze['date'] = $startdate;
		}

	if (!empty($canceldate = trim($event['canceldate']))) {
			try {$canceldt = new \DateTime($canceldate);}
			catch (\Exception $e){	Utilities::alertBadInput ("Illegal Cancel date for event: $canceldate");
			}
			$ze['cancelts'] = $canceldt->format('U');
			$ze['canceldate'] = $canceldt->format('n/j g:i a');
		} else {
			$ze['canceldate'] = $canceldate;
		}

	if (!empty($suspenddate = trim($event['suspenddate']))) {
			try {$suspenddt = new \DateTime($suspenddate);}
			catch (\Exception $e){	Utilities::alertBadInput ("Illegal Suspend date for event: $suspenddate");
			}
			$ze['suspendts'] = $suspenddt->format('U');
			$ze['suspenddate'] = $suspenddt->format('n/j g:i a');
		} else {
			$ze['suspenddate'] = $suspenddate;
		}


		if (!empty($enddate = trim($event['end']))){
			if (! $enddt = new \DateTime($enddate) ){
				Utilities::alertBadInput ("Illegal end date for recurring event: $enddate");
			}
			$ze['end'] = $enddt->format('n/j g:i a');
			$ze['endts'] = $enddt->format('U');
		} else {
			$ze['end'] = $enddate;
		}

		if (empty($event['title'])){
			Utilities::alertBadInput ("Event must have a title");
		}
		$ze['title'] = $event['title'];

		if (empty($event['location'])){
			Utilities::alertBadInput ("Event must have a location");
		}
		$ze['location'] = $event['location'];

		if (empty($event['type'])){
			Utilities::alertBadInput ("Event must have a type");
		}
		$ze['type'] = $event['type'];

		if (empty($event['duration'])){
			Utilities::alertBadInput ("Event must have a duration");
		}
		$ze['duration'] = $event['duration'];

		if (empty($event['suspended'])){
			$ze['suspended'] = false;
		}
		$ze['dayset'] = $event['dayset']??[];
		$ze['note'] = $event['note']??[];

		$ze['reservation'] = (empty($event['reservation'])) ?  false:true;

		$z[] = $ze;
	}
	 //U::echor($z,'checked cal');
	return $z;
}


public function  write_calendar(array $z) {
	//U::echor($z,'ready to write',STOP);

	return $this->CM->writeCache('calendar',$z);
}


public function load_cache() {
		// load dummy if cache file not exists.  Otherwise, is OK to be empty.
		if (! file_exists(CACHE['calendar'])
			|| !$y = json_decode (file_get_contents(CACHE['calendar']), true)
		) {
			$y = self::$dummy_calendar;
		}
		return $y;
}






} #end class



