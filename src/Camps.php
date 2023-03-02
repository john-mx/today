<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;



//END START



class Camps {

private $CM;

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


	foreach (LS::getCampCodes() as $cg){

		$opt = Utilities::buildOptions(LS::getCampStatusCodes(), $camps['cgs'][$cg]['status'] ?? '');
		$camps['cgs'][$cg]['statusopt']  = $opt;

		if ((time() - $camps['cgs'][$cg]['asof'])  < Defs::getTimeout('newest')*60*60):
			$stale = '0';
			$camps['cgs'][$cg]['stalex']= 0;
		elseif ((time() - $camps['cgs'][$cg]['asof'])  < Defs::getTimeout('new')*60*60):
			$stale = '1';
			$camps['cgs'][$cg]['stalex']= 1;


		else: $stale = '2';
			$camps['cgs'][$cg]['stalex']= 2;

		endif;
		$camps[$cg]['stale'] = $stale;

	//	echo "$cg: " ; echo ($rc_newer)? 'rc newer':'manual newer' ; echo  BR;
		$camps['cgs'][$cg]['asof'] = $camps['cgs'][$cg]['asof'] ?? time();
		$camps['cgs'][$cg]['open'] = $camps['cgs'][$cg]['open']?? 1000;
		$camps['cgs'][$cg]['asofHM'] = date('M j g:i a',$camps['cgs'][$cg]['asof']);


	}

// 	U::echor($camps, 'camps prepared');
	return $camps;
}



	public function postCamps($post) {
//  	U::echor($post, 'postCamps');
		$campd = $post;
		$camps = $this->CM->loadCache('camps');
		$campsU = [];

		foreach (LS::getCampCodes() as $cg){

			$campsU[$cg]['status'] = $campd[$cg]['status'];
			$campsU[$cg]['notes'] = $campd[$cg]['notes'];
			if (strlen ( $campd[$cg]['cgupdate']) > 0) { #0 is ok, null is not
				$campsU[$cg]['open'] = $campd[$cg]['cgupdate'];
				$campsU[$cg]['asof'] = time();
			} else { #no change
				$campsU[$cg]['open'] = $camps['cgs'][$cg]['open'] ;
				$campsU[$cg]['asof'] = $camps['cgs'][$cg]['asof'] ;
			}

			if ($campd[$cg]['status'] == 'Closed'){
				$campsU[$cg]['open'] = 0;
				$campsU[$cg]['asof'] = time();
			}
		}

		$camps['cgs']=$campsU;
//U::echor ($campsU, 'camps'); exit;
	$this->CM->writeCache('camps',$camps);

	}

public function prepareDisplayCamps(){
		$camps = $this->CM->loadCache('camps');


// U::echor($camps,'camps');


	$total_open=0;
	foreach (array_keys($camps['cgs'] )as $cg){
		$camps['cgs'][$cg]['status'] = $camps['cgs'][$cg]['status'];
		$camps['cgs'][$cg]['asof'] = $camps['cgs'][$cg]['asof'] ?? time();
		$camps['cgs'][$cg]['open'] = $camps['cgs'][$cg]['open']?? 0;
		$camps['cgs'][$cg]['notes'] = $camps['cgs'][$cg]['notes'];
		$camps['cgs'][$cg]['asofHM'] = date('M j g:i a',$camps['cgs'][$cg]['asof']);
		$camps['cgs'][$cg]['updated'] = $camps['rec.gov_update'];


		if ((time() - $camps['cgs'][$cg]['asof'])  < Defs::getTimeout('newest')*60*60):
			$stale = '0';
			$camps['cgs'][$cg]['stalex']= 0;
		elseif ((time() - $camps['cgs'][$cg]['asof'])  < Defs::getTimeout('new')*60*60):
			$stale = '1';
			$camps['cgs'][$cg]['stalex']= 1;


		else: $stale = '2';
			$camps['cgs'][$cg]['stalex']= 2;
			$camps['cgs'][$cg]['open'] = '';

		endif;
		$camps['cgs'][$cg]['stale'] = $stale;

		if ($camps['cgs'][$cg]['status'] == 'Closed') {
				$camps['cgs'][$cg]['stale'] = '0';
				$camps['cgs'][$cg]['open'] = 0;
		}

		if ($camps['cgs'][$cg]['open'] > 0):++$total_open;endif;
	} #end for each
	$camps['total_open'] = $total_open;

//	U::echor($camps, 'camps prepared');
	return $camps;

}

} #end class
