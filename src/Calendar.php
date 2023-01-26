<?php
namespace DigitalMx\jotr;

//BEGIN START






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
                ),
            );

	 static $empty_cal = array (
	 	'date'=>'',
	 	'days'=> '',
	 	'end'=> '',
	 	'time'=>0,
	 	'location'=>'',
	 	'type'=>'','title'=>'',
	 	'duration' => '',
	 	'suspended' => false,
	 	'reservation' => false,
	 	'note'=>''
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

public function parse_time($t){
	// converts time in string to hour, min
	// preg_match('/(\d+)\:?(\d*) *(a|A|p|P).*/',$t,$m);
// 	#Utilities::echor ($m,"match on $t",false);
//
// 	if (sizeof($m)!=4){return false;}
	return true;
// 	echo date("G:i", strtotime($t));
// 	$time = array($hrs,$mins);
// 	echo "Time: $hrs:$mins" . BR;
// 	return $time;
}
public function add_types ($cal) {
	// adds type select options to each entry

	foreach ($cal as $event){
		$event['typeoptions']  = Utilities::buildOptions(self::$eventtypes,$event['type']);
		$z[] = $event;
	}
return $z;
}

public function post_calendar($cal){

	$cal = $this->check_calendar($cal);
	$cal = self::filter_calendar($cal,0);
	$this->write_calendar($cal);
}

public static function filter_calendar(array $calendar,int $transform = 0) {
	/*
		removes expired events from calendar and sort by date
		calenar = array (
			0 = array (date,days,end,time, type,title,location,note),
			1 = ...
			);
		Only events (scheduled or recurring)  with a time entry are retained.

		Recurring events have days (1..7, where 1 is Sundays)

	Transform is number of days of recurring events to transform
		into individual dates.  If transform is 0, the recurring
		events are left as is (as for admin screen).  If it's 3,
		then any recurring ewvents in the next 3 days are inserted as scheduled events.  (as for display).

	Removes suspended events unless transform = 0
	*/



	$z=[];
//echo "Transform $transform" . BR;
	foreach ($calendar as $event){ #keep if these conditions:
// Utilities::echor ($event,'foreach event');
		if (empty ($event['time'])) {continue;} #drop

		if ( empty($event['suspended'])) $event['suspended'] = false;


#	echo "Testing " . $event['title'] . BR;


 // test for recurring
		if ($event['suspended']) {
			if ($transform == 0) {
				$event['dt'] = 0;
				$z[] = $event;
			}
			else{ continue;} #skip if not admin
		}
		elseif (empty ($event['days']) && !empty ($event['date'] )) { //scheduled
			$z[] = self::parse_scheduled($event);
			#echo "added scheduled {$event['title']}" . BR;

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
					if (! $enddt = new \DateTime($enddate,$this->tz) ){
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

// Utilities::echor($z, 'new cal', true);


	return ($z);
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
				return $schevent;
				echo "added {$event['title']} on $testdate." . BR;
		}



	}


public static	function parse_scheduled ($event) {
			$edate = $event['date'] . ' ' . $event['time'];
			$edt = new \DateTime($edate) ;
			$now = new \DateTime();
			//$today->setTimeZone('America/Los_Angeles');

		#echo "Set dt to $edate" . BR;


		if ($edt < $now){return [];} #past date
		$dts = $edt->format('U');
		$event['dt'] = $dts;
		//
		return $event;

	}

#@	Utilities::echor($z, 'presort', false);




public function check_calendar(array $calendar) {
// check post data
	$z=[];
	foreach ($calendar as $event){
		if (empty($time = $event['time'])){continue;}
		if (isset($event['delete'])) continue;

		if (!$t = $this->parse_time($time)){
				Utilities::alertBadInput ("Time '$time'. Time must be hr:mins am|pm");
		}


		if (!empty($startdate = trim($event['date']))) {
			// if empty, then today will be used for start
			try {$startdate = new \DateTime($startdate);}
			catch (\Exception $e){	Utilities::alertBadInput ("Illegal date for event: $startdate");
			}
		}

		if (!empty($enddate = trim($event['end']))){
			if (! $enddt = new \DateTime($enddate) ){
				Utilities::alertBadInput ("Illegal end date for recurring event: $enddate");
			}
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
	return $z;
}

#Utilities::echor ($z,'new',true);
public function write_calendar(array $z) {

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



