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



	foreach (array_keys($camps) as $cg){
		$opt = Utilities::buildOptions(Defs::$cgstatus, $camps[$cg]['status'] ?? '');
		$camps[$cg]['statusopt']  = $opt;

	//	echo "$cg: " ; echo ($rc_newer)? 'rc newer':'manual newer' ; echo  BR;
		$camps[$cg]['asof'] = $camps[$cg]['asof'] ?? time();
		$camps[$cg]['open'] = $camps[$cg]['open']?? 100;
		$camps[$cg]['asofHM'] = date('M j g:i a',$camps[$cg]['asof']);
	}
//	U::echor($camps, 'camps prepared');
	return $camps;


	}
}
