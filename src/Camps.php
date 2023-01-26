<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//END START



class Camps {

function __construct($c){
	$this->CM = $c['CacheManager'];

}


	public function prepareAdminCamps() {
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


	$camps = $this->CM->loadCache('camps');

// U::echor($camps,'camps');


	foreach (array_keys(Defs::$campsites) as $cg){
		$opt = Utilities::buildOptions(Defs::$cgstatus, $camps[$cg]['status'] ?? '');
		$camps[$cg]['statusopt']  = $opt;

	//	echo "$cg: " ; echo ($rc_newer)? 'rc newer':'manual newer' ; echo  BR;
		$camps[$cg]['asof'] =$camps[$cg]['asof'] ?? time();
		$camps[$cg]['open'] = $camps[$cg]['open']?? 100;
		$camps[$cg]['asofHM'] = date('M j g:i a',$camps[$cg]['asof']);
	}
//	U::echor($camps, 'camps prepared');
	return $camps;
}



	public function postCamps($post) {
 	//U::echor($post, 'postCamps');
		$campd = $post;
		$camps = $this->CM->loadCache('camps');
		$campsU = [];

		foreach (array_keys(Defs::$campsites) as $cg){

			$campsU[$cg]['status'] = $campd[$cg]['status'];
			$campsU[$cg]['notes'] = $campd[$cg]['notes'];
			if (strlen ( $campd[$cg]['cgupdate']) > 0) { #0 is ok, null is not
				$campsU[$cg]['open'] = $campd[$cg]['cgupdate'];
				$campsU[$cg]['asof'] = time();
			} else { #no change
				$campsU[$cg]['open'] = $camps[$cg]['open'] ;
				$campsU[$cg]['asof'] = $camps[$cg]['asof'] ;
			}

			if ($campd[$cg]['status'] == 'Closed'){
				$campsU[$cg]['open'] = 0;
				$campsU[$cg]['asof'] = time();
			}
		}
 //	U::echor ($campsU, 'campsU');


	$this->CM->writeCache('camps',$campsU);

	}

public function prepareDisplayCamps(){
		$camps = $this->CM->loadCache('camps');


// U::echor($camps,'camps');


	$total_open=0;
	foreach (array_keys($camps) as $cg){
		$camps['cgs'][$cg]['status'] = $camps[$cg]['status'];
		$camps['cgs'][$cg]['asof'] = $camps[$cg]['asof'] ?? time();
		$camps['cgs'][$cg]['open'] = $camps[$cg]['open']?? 0;
		$camps['cgs'][$cg]['notes'] = $camps[$cg]['notes'];
		$camps['cgs'][$cg]['asofHM'] = date('M j g:i a',$camps[$cg]['asof']);
		$camps['updated'] = file_get_contents(REPO_PATH . '/data/rec.gov_update');


		if ((time() - $camps[$cg]['asof'])  < 3*60*60): $stale = '#3F3';
		elseif ((time() - $camps[$cg]['asof'])  < 12*60*60): $stale = '#FF3';
		else: $stale = '#F33';
		endif;
		$camps['cgs'][$cg]['stale'] = $stale;

		if ($camps[$cg]['status'] == 'Closed') {
				$camps['cgs'][$cg]['stale'] = '#FFF';
				$camps['cgs'][$cg]['open'] = 0;
		}

		if ($camps['cgs'][$cg]['open'] > 0):++$total_open;endif;
	} #end for each
	$camps['total_open'] = $total_open;

//	U::echor($camps, 'camps prepared');
	return $camps;

}

} #end class
