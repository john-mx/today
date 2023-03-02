<?php
namespace DigitalMx\jotr;

//BEGIN START
use DigitalMx\jotr\Utilities as U;





//END START


class Calendar {
	static $dummy_calendar = array
			 (
            0 => array
                (
                		'date' => 'Nov 28, 2022',
							'days' => '',
							'end'=> '',
							'time' => '2pm',
                    'location' => 'Indian Cove amphitheater',
                    'type' => 'Ranger Program',
                    'title' => 'Adaptations to the the Desert',
                    'duration' => '30 min',
                    'suspended' => true,
                    'canceldate' => '',

                    'note' => '',
                ),

            '1' => array
                (
                    'date' => '',
							'days' => '45',
							'end'=> '',
							'time' => '2pm',
                    'location' => 'Discovery Trail trailhead',
                    'type' => 'Walk and Talk',
                    'title' => 'Where these rocks came from',
                    'duration' => '30 min',
                  'suspended' => true,
                    'note' => 'Gather at the crosswalk on Park Drive',
                    'canceldate' => '',
                ),

            '2' => array
                (
                    'date' => '',
							'days' => '016',
							'end'=> '',
							'time' => '2pm',
                    'location' => 'Joshua Tree Cultural Center',
                    'type' => 'Ranger Talk',
                    'title' => 'The Local Tribes',
                    'duration' => '30 min',
                    'suspended' => true,
                    'note' => '',
                    'canceldate' => '',
                ),
            );

	 static $empty_cal = array (
	 	'date'=>'',
	 	'days'=> '',
	 	'end'=> '',
	 	'time'=>0,
	 	'location'=>'',
	 	'type'=>'',
	 	'title'=>'',
	 	'duration' => '',
	 	'suspended' => false,
	 	'reservation' => false,
	 	'note'=>'',
	 	'canceldate' => '',
	 	);

	 static $eventtypes = array(

	'Evening Program',
	'Guided Activity',
	'Guided Tour',
	'Ranger Chat',
	'Ranger Walk',
	'Social Event',
	);

	private $tz;
	private $CM;

	public function __construct($c) {
		$this->tz = new \DateTimezone('America/Los_Angeles');
		$this->CM = $c['CacheManager'];
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
	$cal['events']= $z;
return $cal;
}

public function post_calendar($cal){
// U::echor($cal,'calpost');
	$cal['events'] = $this->check_events($cal['events']);
//U::echor($cal,'after check');
	$cal['events'] = self::filter_events($cal['events'],0);
// U::echor ($cal ,'to write');
	$this->write_calendar($cal);
	return true;
}

public static function filter_events(array $events,int $transform = 0) {
	/*
		removes expired events from calendar and sort by date
		calenar = array (
			0 = array (date,days,end,time, type,title,location,note),
			1 = ...
			);
		Only events (scheduled or recurring)  with a time entry are retained.

		Recurring events have days (1..7, where 1 is Sundays)

	Transform is number of days of recurring or scheduled to transform
		into individual dates.  If transform is 0, the recurring
		events are left as is (as for admin screen).  If it's 3,
		then any recurring ewvents in the next 3 days are inserted as scheduled events.  (as for display).

	Removes suspended events unless transform = 0
	*/



	$z=[];
//echo "Transform $transform" . BR;
	foreach ($events as $event){ #keep if these conditions:
// Utilities::echor ($event,'foreach event');
		if (empty ($event['time'])) {continue;} #drop

		if ( empty($event['suspended'])) $event['suspended'] = false;


#	echo "Testing " . $event['title'] . BR;


		if ($event['suspended']) {
			if ($transform == 0) {
				$event['dt'] = 0;
				$z[] = $event;
			}
			else{ continue;} #skip if not admin
		}
		elseif (empty ($event['days']) && !empty ($event['date'] )) { //scheduled event
			$z[] = self::parse_scheduled($event,$transform);
			//echo "added scheduled {$event['title']}" . BR;

		} elseif ($transform == 0 ) { #keep for admin sccreen, but don't expand
			$event['dt'] = 0; // unscheduled; float to top
			$z[] = $event;
			#echo "passed for admih scheduled {$event['title']}" . BR;
		} else { // active recurring
				// prepare starting date
				if (empty($edate = trim($event['date']))){
					$edate = date('M d,Y'); #today
				}
				$begindate = $edate . ' ' . trim($event['time']);
				try{ $begindt = new \DateTime($begindate,new \DateTimeZone('America/Los_Angeles')); }
				catch (\Exception $e) {Utilities::alertBadInput ("Illegal start date for recurring event: $begindate");
					exit; // should have got during prepare function
				}
				if (!empty($enddate = trim($event['end']))){
					if (! $enddt = new \DateTime($enddate) ){
						Utilities::alertBadInput ("Illegal end date for recurring event: $enddate");
						exit; // should have got at prepare
					}
				} else {$enddt=[];}


				for ($i=0;$i<$transform;++$i){
					$cevent = self::parse_recurring($event,$begindt,$enddt,$i);
					if ($cevent) {
						$z[] = $cevent;
						#echo "added repeating {$event['title']}" . BR;
						#Utilities::echor($cevent);
					}
				}
			}


	}
// Utilities::echor($z, 'presort cal', false);
		$z = Utilities::element_sort($z, 'dt');


	$cal=$z;

// U::echor($cal, 'post filterl');
	return $cal;
}

private static function parse_recurring($event,$begindt,$enddt,$i) {
		$now = new \DateTime();
		#create actual event list
		$testdt = new \DateTime($event['time']); // today at sched time
	$testdt->modify (" + $i day");

		$testdate = $testdt->format('M d');


		if ($begindt>$testdt){
		#echo "skip: begin $begindate > $testdate." . BR;
				return [];
		}

		if ( !empty ($event['end']) && $enddt < $testdt){
			#echo "skip: end $enddate < $testdate." . BR;
					return [];

		}
		if ($testdt < $now) {// past
			return [];
		}

		$wd = $testdt->format('w'); #get day of werek
#echo "... iteration $i " . ' on ' . $testdt->format('M d') . " day is $wd." . BR;

		if (strpos ($event['days'],$wd) !== false){
				$schevent = $event;
#echo "Set time {$event['time']} to $hrs, $mins. " . BR;
				$schevent['date'] = $testdt->format('M d Y');
				$schevent ['days'] = '';
				$dts = $testdt->format('U'); #timestamp
	//	echo "Test dt " .$testdt->format('M d Y H:i') . 'for' . $event['time']  . BR;
				$schevent['dt'] = $dts;
// 			echo "added recurring {$event['title']} on $testdate." . BR;
				$schevent['cancelled'] = (!empty($event['canceldt']) && ($dts < $event['canceldt']) )? 1:0;

				return $schevent;
		}



	}


public static	function parse_scheduled ($event,$transform) {

			try {$etime =
				$etime = trim($event['time']);
				$edate = $event['date'] . ' ' . $event['time'];
				$edts = strtotime($edate);
				$edt = new \DateTime();
				$edt ->setTimestamp($edts);
				$edt->setTimezone(new \DateTimeZone('America/Los_Angeles'));
				$now = new \DateTime();
			}
			catch (Exception $e) {
				U::alertBadInput ($e->getMessage());
			}

// 		echo "now: " . $now->format('d H:i') . BR;
// 		echo "edt: " . $edt->format('d H:i') . BR;
		if ($edt < $now){
			//echo "expired $edate; ";
			return [];
			} #past date

		$diff = $now->diff($edt)->days;
		if ($transform && ($diff > $transform)) {
			//echo "late $edate; ";
		return [];}
		$event['cancelled'] = (!empty($event['canceldt']) && ($edts < $event['canceldt']) ) ? 1:0;
		$event['dt'] = $edts;
		//
		return $event;

	}

#@	Utilities::echor($z, 'presort', false);




public function check_events(array $events) {
// check post data
	//U::echor ($events,'check in');
	$z=[];
	foreach ($events as $event){
		//U::echor($event,'event' );
		if (empty($time = $event['time'])){continue;}
		if (isset($event['delete'])) continue;

		$event['time'] = U::format_time($time);


		if (!empty($startdate = trim($event['date']))) {
			// if empty, then today will be used for start
			try {$startdt = new \DateTime($startdate);}
			catch (\Exception $e){	Utilities::alertBadInput ("Illegal Start date for event: $startdate");
			}
			$event['startdatedt'] = $startdt->format('U');
		}

	if (!empty($canceldate = trim($event['canceldate']))) {
			// if empty, then today will be used for start
			try {$canceldt = new \DateTime($canceldate);}
			catch (\Exception $e){	Utilities::alertBadInput ("Illegal Cancel date for event: $canceldate");
			}
			$event['canceldt'] = $canceldt->format('U');
		}
		if (!empty($enddate = trim($event['end']))){
			if (! $enddt = new \DateTime($enddate) ){
				Utilities::alertBadInput ("Illegal end date for recurring event: $enddate");
			}
			$event['enddt'] = $enddt->format('U');
		}

		if (empty($event['title'])){
			Utilities::alertBadInput ("Event must have a title");
		}

		if (empty($event['location'])){
			Utilities::alertBadInput ("Event must have a location");
		}

		if (empty($event['type'])){
			Utilities::alertBadInput ("Event must have a type");
		}

		if (empty($event['duration'])){
			Utilities::alertBadInput ("Event must have a duration");
		}

		if (empty($event['suspended'])){
			$event['suspended'] = false;
		}

	if (empty($event['reservation'])){
			$event['reservation'] = false;
		}
		// convert "day4 = on" to days string
		$days='';
		for ($j=0;$j<7;++$j){
			if (isset ($event['day'.$j] )) $days .=strval($j);
		}
		$event['days'] = $days;

		$z[] = $event;
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



