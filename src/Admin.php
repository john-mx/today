<?php
namespace DigitalMx\jotr;


use DigitalMx\jotr\Log;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;




//END START


/* class admin to prepare and post the admin screens

*/

class Admin {

private static $empty_camps = array(
	"ic" => "20","jr" => "0","sp" => "0","hv" => "0","be" => "0","wt" => "0","ry" => "0","br" => "0","cw"=>"0"
	);


public function __construct($c){

	$this->Today = $c['Today'];
	$this->CM = $c['CacheManager'];
		$this->Cal = $c['Calendar'];
		$this->Camps = $c['Camps'];

}

public function prepare_admin() {
// get sections needed for the admin form

	if (!$admin = $this->CM->loadCache('admin')){
	 	Log::error ("Could not load cache admin");
	 	exit;

	 }
	 //transfer unmodified field
	 foreach (['pithy','fire_level','announcements',
	 'advice','rdelay','alert_alt',
	 ] as $f){
	 	$y[$f] = $admin[$f];
	 }
 	//Utilities::echor ($y, 'read admin cache', NOSTOP);

	// set firelevel options array
	$fire_levels = array_keys(Defs::$firewarn);
	$y['fire_level_options'] = Utilities::buildOptions($fire_levels,$admin['fire_level']);

// camps



// rotation
	$rchecked = [];
	$rotators = $admin['rotate'] ?? [];
	foreach (array_keys(Defs::$rpages) as $pid){
		if (in_array($pid,$rotators)){$rchecked[$pid] = 'checked';}
	}
	$y['rchecked'] = $rchecked;

// alerts
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
$r['camps'] = $this->Camps->prepareAdminCamps() ;
	$r['galerts'] = $this->CM->loadCache('galerts');

// calendar
	$calendar = $this->Cal->filter_calendar($this->CM->loadCache('calendar'),0);

#add 3 blank recordsw
	for ($i=0;$i<3;++$i) {
		$calendar[] = $this->Cal::$empty_cal;
	}

	$calendar = $this->Cal->add_types($calendar);
	$r['calendar'] = $calendar;

//  Utilities::echor ($r, 'r to admin',NOSTOP);
	return $r;
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



	$y['rotate'] = $post['rotate']; //array
//Utilities::echor($y,'y',STOP);
	$y['rdelay'] = $post['rdelay']; #rotation time


// check alerts
	foreach (['alertA'] as $alertID) {
		$y[$alertID] = $this->checkAlert($post[$alertID]);
	}
	//Utilities::echor($y ,'post',STOP);

	$this -> CM->writeCache('admin',$y);



	$this->Camps->postCamps($post['camps']);

	$this->Cal->post_calendar($post['calendar']);


}

public function build_admin_calendar() {
	if (!$z=$this->loadCache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }

#	Utilities::echor($z,'calendar',STOP);
	$y=$this->Cal->filter_calendar($z,0);
	return ['calendar' => $y];
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


}
