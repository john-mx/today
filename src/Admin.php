<?php
namespace DigitalMx\jotr;


use DigitalMx\jotr\Log;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;




//END START


/* class admin to prepare and post the admin screens from both
admin and ranger php pages

*/

class Admin {

private $DM;
private $CM;
private $Cal;
private $Camps;


private static $empty_camps = array(
	"ic" => "20","jr" => "0","sp" => "0","hv" => "0","be" => "0","wt" => "0","ry" => "0","br" => "0","cw"=>"0"
	);


public function __construct($c){

	$this->DM = $c['DisplayManager'];
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
	 'advice','rdelay','alert_alt','fixedAdvice'
	 ] as $f){
	 	$y[$f] = $admin[$f];
	 }
 	//Utilities::echor ($y, 'read admin cache', NOSTOP);

	// set firelevel options array
	$fire_levels = Defs::getFireKeys();
	$y['fire_level_options'] = Utilities::buildOptions($fire_levels,$admin['fire_level']);

// camps



// rotation
	$rchecked = [];
	$rotators = $admin['rotate'] ?? [];
	foreach (LS::getRemotePageKeys() as $pid){
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
	$r['galerts'] = $this->DM->build_topic_galerts();

// calendar
$events = $this->CM->loadCache('calendar')['events'];
//U::echor($events,'loaded from cache');
	$events = $this->Cal->filter_events($events,0);
//U::echor($events,'Post filter');
#add 3 blank recordsw
	for ($i=0;$i<3;++$i) {
		$events[] = $this->Cal::$empty_cal;
	}
//U::echor($events,'post blank adds');
	$events = $this->Cal->add_types($events);
	$r['calendar'] = $events;

// Utilities::echor ($r, 'r to admin');
	return $r;
}



public function post_admin ($post) {
 /* insert posted data and dependencies into cacjes

	x = current admin cache
	y = updated vars from post
	new cache = merge(x,y), so onoy vars in the post get updated.
*/
//  Utilities::echor ($post, 'Posted' );
	$x = $this->CM->loadCache('admin');
	$y = [];
	$fields = ['announcements','pithy','fire_level','alert_alt','advice',
		'rdelay'];

	foreach ($fields as $field){
		if (isset($post[$field]))
			$y[$field] = trim($post[$field]);

	}
	$fields = ['announcements','advice'];
	foreach ($fields as $field){
		$y[$field] = preg_replace("/\n\r?[\n\r]+/","\n",$y[$field]);
		//U::echor($y[$field],$field,STOP);
	}
//  admin cache
	if (isset($post['rotate']))
		$y['rotate'] = $post['rotate']; //array

// check alerts
	if (isset($post['alertA']))
		$y['alertA'] = $this->checkAlert($post['alertA']);

	if (!empty($y)){
		$z = array_merge($x,$y); // updates x for vars in post
		//Utilities::echor($z ,'post',STOP);

		$z['updated'] = date('d M H:i');
		$this -> CM->writeCache('admin',$z);
	}


	if ($post['campu'])
		$this->Camps->postCamps($post['campu']);

	if ($post['events'])
//U::echor($post['events'],'post');
	$this->Cal->post_calendar(['events'=>$post['events']]);

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
 // Utilities::echor($alert,'start alert check');
	if (! isset($alert['title']) ){return [];}
	if (empty (trim($alert['title']))) {
		//echo "cleared";
		$y['expires'] = $y['text'] = $y['title'] = '';

	} else {
		$y['title'] = $alert['title'];
		$y['text'] =  $alert['text'];
		//echo 'exp ' .$alert['expires'] . BR;
		if (empty($alert['expires'])){
			Utilities::alertBadInput("Must have an expiration date for an alert");
		}
		try{$alertAx = new \DateTime($alert['expires']);}
		catch (\Exception $e) {
			Utilities::alertBadInput ("Cannot understand date/time: {$alert['expires']}");
		}
		$alertAxts = $alertAx->format('U');
		if ($alertAxts < time()) {
			Utilities::alertBadInput("Expiration less than now.  To delete item, remove the title");
		}

		$y['expires'] = $alertAxts;
	}
 // Utilities::echor($y,'checked alert', STOP );
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
