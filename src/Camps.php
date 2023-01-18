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
	$campsRec = $this->CM->loadCache('campsRec') ;

// U::echor($camps,'camps');
// U::echor($campsRec, 'campsRec',);


	foreach (array_keys($camps) as $cg){
		$opt = Utilities::buildOptions(Defs::$cgstatus, $camps[$cg]['status'] ?? '');
		$camps[$cg]['statusopt']  = $opt;
		$rc_newer = isset($campsRec[$cg]) && $campsRec[$cg]['asof'] > $camps[$cg]['asof'];
	//	echo "$cg: " ; echo ($rc_newer)? 'rc newer':'manual newer' ; echo  BR;
		$camps[$cg]['asof'] = $rc_newer?
			$campsRec[$cg]['asof'] :$camps[$cg]['asof'] ?? time();
		$camps[$cg]['open'] = $rc_newer?
			$campsRec[$cg]['open'] :$camps[$cg]['open']?? 100;
		$camps[$cg]['asofHM'] = date('M j g:i a',$camps[$cg]['asof']);
	}
//	U::echor($camps, 'camps prepared');
	return $camps;
}



	public function postCamps($post) {
// 	U::echor($post, 'postCamps');
		$campd = $post;
		$camps = $this->CM->loadCache('camps');
		$campsRec =$this->CM->loadCache('campsRec');

		foreach ($campd as $cg=>$cgd){
		//U::echor($cgd,"key $cg");
			if ($cgd['status'] == 'Reserved'){
				$camps[$cg]['status'] = 'Reserved';
				$camps[$cg]['notes'] = $cgd['notes'];
				if (!empty ($cgo = $cgd['cgupdate'])){
					$camps[$cg]['open'] = $cgo;
					$camps[$cg]['asof'] = time();
				}

			} elseif ($cgd['status'] == 'First'){
				$camps[$cg]['status'] = 'First';
				$camps[$cg]['notes'] = $cgd['notes'];
				if (!empty ($cgo = $cgd['cgupdate'])){
					$camps[$cg]['open'] = $cgo;
					$camps[$cg]['asof'] = time();
				}
			} else { # is closed
				$camps[$cg]['status'] = 'Closed';
				$camps[$cg]['notes'] = $cgd['notes'];
				$camps[$cg]['open'] = 0;
				$camps[$cg]['asof'] = time();
			}
		}
// 		U::echor ($camps, 'camps');
// 		U::echor ($campsRec, 'campsRec');

		$this->CM->writeCache('camps',$camps);
		$this->CM->writeCache('campsRec',$campsRec);
	}

public function prepareDisplayCamps(){
		$camps = $this->CM->loadCache('camps');
	$campsRec = $this->CM->loadCache('campsRec') ;

// U::echor($camps,'camps');
// U::echor($campsRec, 'campsRec',);


	foreach (array_keys($camps) as $cg){
		$opt = Utilities::buildOptions(Defs::$cgstatus, $camps[$cg]['status'] ?? '');
		$camps[$cg]['statusopt']  = $opt;
		$rc_newer = isset($campsRec[$cg]) && $campsRec[$cg]['asof'] > $camps[$cg]['asof'];
	//	echo "$cg: " ; echo ($rc_newer)? 'rc newer':'manual newer' ; echo  BR;
		$camps[$cg]['asof'] = $rc_newer?
			$campsRec[$cg]['asof'] :$camps[$cg]['asof'] ?? time();
		$camps[$cg]['open'] = $rc_newer?
			$campsRec[$cg]['open'] :$camps[$cg]['open']?? 100;
		$camps[$cg]['asofHM'] = date('M j g:i a',$camps[$cg]['asof']);
	}
//	U::echor($camps, 'camps prepared');
	return $camps;


	}
}
