<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



//BEGIN START





//END START



class CgOpens {

function __construct($c){

	$this->Plates = $c['Plates'];

	$this->test_opens = array(
		array (
			'd' => '11/23/2022',
			'ic' => 4,
			'jr' => 2,
			'sp' => 3,
			'hv' => 0,
			'be' => 0,
			'wt' => 3,
			'br' => 0,
			'cw' => 9,
			'ry' => 0,
			),
		 array (
			'd' => '11/24/2022',
			'ic' => 9,
			'jr' => 2,
			'sp' => 3,
			'hv' => 2,
			'be' => 0,
			'wt' => 3,
			'br' => 0,
			'cw' => 9,
			'ry' => 4,
			),
		 array (
			'd' => '11/25/2022',
			'ic' => 25,
			'jr' => 2,
			'sp' => 3,
			'hv' => 0,
			'be' => 0,
			'wt' => 3,
			'br' => 0,
			'cw' => 9,
			'ry' =>10,
			),
		);

}

	public function get_opens() {
		if (! $opens = $this->Today->load_cache('cgopen')){
			$opens = $this->test_opens;
		}
		return $opens;
	}
	public function get_mtime() {
		if (file_exists(CACHE['cgopen'])){
			$mtime = filemtime (CACHE['cgopen']);
		}else{
			$mtime = time();
		}
		$asof =	date('M d H:i a', $mtime);
		return $asof;
	}
		//Utilities::echor($opens,'show_opens');




	public function save_opens($opens){
		$this->Today->write_cache('cgopen',$opens);
	}



}
