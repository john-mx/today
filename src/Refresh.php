<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require  './init.php';
	





class Refresh {


public function refreshCaches($force=false) {
	public function __construct ($c){
		$this->Today = $c['Today'];
	}
	Log::info ("Starting cache refresh cycle");

// refreshes all the external caches, if they are due
	#$caches = ['wapi','airq','airowm','wgov','airnow','galerts'];
		if ($this->Today->over_cache_time('wapi') > 0 || $force) {
				$Today->rebuild_cache_wapi();

		}
		if ($this->Today->over_cache_time('airq') > 0|| $force) {
			$this->Today->rebuild_cache_airq();

		}
		if ($this->Today->over_cache_time('airowm')> 0 || $force) {
			$this->Today->rebuild_cache_airowm();
		}
		if ($this->Today->over_cache_time('wgov')> 0 || $force) {
				$this->Today->rebuild_cache_wgov();
		}
		if ($this->Today->over_cache_time('airnow')> 0 || $force) {
				$this->Today->rebuild_cache_airnow();
		}
		if ($this->Today->over_cache_time('galerts')> 0 || $force) {
				$this->Today->rebuild_cache_galerts();
		}

			#	$this -> rebuild_properties('jr');
	Log::info ("Completed cache refresh cycle");
}

} // end class
