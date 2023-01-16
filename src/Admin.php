<?php
namespace DigitalMx\jotr;


use DigitalMx\jotr\Log;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;




//END START


/* class admin to prepare and post the admin screens

*/

class Admin {

public function __construct($c){

	$this->Today = $c['Today'];
}

public function prepare_admin() {
// get sections needed for the admin form

	if (!$admin = $this->Today->load_cache('admin')){
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
$y['camps'] = $this->adminCamps() ;


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

	$r['galerts'] = $this->load_cache('galerts');



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

public function adminCamps() {
	/* camps=> [
		cgcode => [
			statusopt => code for open/res/closed,
			open => number,
			asof => ts,
			note => text,
			,
		],
		....
	]
	*/


	$camps = $this->Today->load_cache('camps');
	$campsRc = $this->Today->load_cache('camps-rc') ?? [];

	foreach (array_keys(Defs::$campsites) as $cgcode){
		$opt = Utilities::buildOptions(Defs::$cgstatus, $camps[$cgcode]['cgstatus']);
		$campsa[$cgcode]['statusopt']  = $opt;
		$campsa[$cgcode]['notes'] = $camps[$cgcode]['cgnotes']?? '';
		$rc_newer = isset($campsRc[$cgcode]) && $campsRc[$cgcode]['asof'] > $camps[$cgcode]['asof'];
		$campsa[$cgcode]['asof'] = $rc_newer?
			$campsRc[$cgcode]['asof'] :$camps[$cgcode]['asof'];
		$campsa[$cgcode]['open'] = $rc_newer?
			$campsRc[$cgcode]['open'] :$camps[$cgcode]['open'];

	}

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

public function build_admin_calendar() {
	if (!$z=$this->load_cache('calendar')) {
	 	Log::error ("Could not load cache calendar");
	 	return [];
	 }

#	Utilities::echor($z,'calendar',STOP);
	$y=$this->Cal->filter_calendar($z,0);
	return ['calendar' => $y];
}



}
