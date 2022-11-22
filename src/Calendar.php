<?php
namespace DigitalMx\jotr;

ini_set('display_errors', 1);

//BEGIN START
	require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;


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
	 	'note'=>''
	 	);

	//$tz = new \DateTimeZone('America/Los_Angeles');

function element_sort(array $array, string $on, $order=SORT_ASC)
{
	/* copied from php manual.
		 sorts a list of arrays by one of the elemnts
		array (
			123 => array (
				'name' => 'asdfl',
				...
			124 => ...

		$sorted = element_sort($unsorted, 'name');


	*/

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
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
// 	#u\echor ($m,"match on $t",false);
//
// 	if (sizeof($m)!=4){return false;}
	return true;
// 	echo date("G:i", strtotime($t));
// 	$time = array($hrs,$mins);
// 	echo "Time: $hrs:$mins" . BR;
// 	return $time;
}
public function add_types ($cal) {
	// adds type selecty options to each entry
	$eventtypes = array(
	'Guided Activity',
	'Ranger Talk',
	'Guided Hike',
	'Short Program',
	);
	foreach ($cal as $event){
		$event['typeoptions']  = u\buildOptions($eventtypes,$event['type']);
		$z[] = $event;
	}
return $z;
}

public function filter_calendar(array $calendar,int $transform = 0) {
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
		events are left as is (as for edit screen).  If it's 3,
		then any recurring ewvents in the next 3 days are inserted as scheduled events.  (as for display).

	*/

	$z=[];

	foreach ($calendar as $event){ #keep if these conditions:
		if (empty ($event['time'])) {continue;} #nope

#	echo "Testing " . $event['title'] . BR;


 // test for recurring

		if (empty ($event['days']) && !empty ($event['date'] )) {
			$z[] = $this->parse_scheduled($event);
			#echo "added scheduled ${event['title']}" . BR;

		} elseif ($transform == 0) { #return for admin sccreen
			$event['dt'] = 0;
			$z[] = $event;
			#echo "passed for admih scheduled ${event['title']}" . BR;
		} else { // recurring
				// prepare starting date
				if (empty($edate = trim($event['date']))){
					$edate = date('M d,Y'); #today
				}
				$begindate = $edate . ' ' . trim($event['time']);
				try{ $begindt = new \DateTime($begindate); }
				catch (\Exception $e) {u\alertBadInput ("Illegal start date for recurring event: $begindate");
					exit; // should have got during prepare function
				}
				if (!empty($enddate = trim($event['end']))){
					if (! $enddt = new \DateTime($enddate,) ){
						u\alertBadInput ("Illegal end date for recurring event: $enddate");
						exit; // should have got at prepare
					}
				} else {$enddt=[];}


				for ($i=0;$i<=$transform;++$i){
					$cevent = $this->parse_recurring($event,$begindt,$enddt,$i);
					if ($cevent) {
						$z[] = $cevent;
						#echo "added repeating ${event['title']}" . BR;
						#u\echor($cevent);
					}
				}
			}


	}
#	u\echor($z, 'presort cal', false);
		$z = $this->element_sort($z, 'dt');

#u\echor($z, 'new cal', true);

	return ($z);
}

	function parse_recurring($event,$begindt,$enddt,$i) {

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


		$wd = $testdt->format('w'); #get day of werek
#echo "... iteration $i " . ' on ' . $testdt->format('M d') . " day is $wd." . BR;

		if (strpos ($event['days'],$wd) !== false){
				$schevent = $event;
#echo "Set time ${event['time']} to $hrs, $mins. " . BR;
				$schevent['date'] = $testdt->format('M d Y');
				$schevent ['days'] = '';
				$dts = $testdt->format('U'); #timestamp
				$schevent['dt'] = $dts;
				return $schevent;
#				echo "added ${event['title']} on $testdate." . BR;
		}



	}


	function parse_scheduled ($event) {
			$edate = $event['date'] . ' ' . $event['time'];
			$edt = new \DateTime($edate) ;
			$today = new \DateTime();

		#echo "Set dt to $edate" . BR;


		if ($edt < $today){return [];} #past date
		$dts = $edt->format('U');
		$event['dt'] = $dts;
		//
		return $event;

	}

#@	u\echor($z, 'presort', false);




public function prepare_calendar(array $calendar) {
	$z=[];
	foreach ($calendar as $event){
		if (empty($time = $event['time'])){continue;}

		if (!$t = $this->parse_time($time)){
				u\alertBadInput ("Time '$time'. Time must be hr:mins am|pm");
		}


		if (!empty($startdate = trim($event['date']))) {
			// if empty, then today will be used for start
			try {$startdate = new \DateTime($startdate);}
			catch (\Exception $e){	u\alertBadInput ("Illegal date for event: $startdate");
			}
		}

		if (!empty($enddate = trim($event['end']))){
			if (! $enddt = new \DateTime($enddate) ){
				u\alertBadInput ("Illegal end date for recurring event: $enddate");
			}
		}

		if (empty($event['title'])){
			u\alertBadInput ("Event must have a title");
		}

		if (empty($event['location'])){
			u\alertBadInput ("Event must have a location");
		}

		if (empty($event['type'])){
			u\alertBadInput ("Event must have a type");
		}

		if (empty($event['duration'])){
			u\alertBadInput ("Event must have a duration");
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

#u\echor ($z,'new',true);
public function write_cache(string $section,array $z) {

	file_put_contents(CACHE[$section],json_encode($z));
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



