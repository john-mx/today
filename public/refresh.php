<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require  './init.php';
	use DigitalMx as u;
	use DigitalMx\jotr\Today;

	$Today = $container['Today'];


//END START
Log::info ("Starting cache refresh cycle");
echo	$Today->refresh_caches ();
echo "Done";

exit;

function refresh_caches($force=false) {


// refreshes all the external caches, if they are due
	#$caches = ['wapi','airq','airowm','wgov','airnow','galerts'];
		if ($this->over_cache_time('wapi') > 0 || $force) {
				$this->rebuild_cache_wapi();

		}
		if ($this->over_cache_time('airq') > 0|| $force) {
			$this->rebuild_cache_airq();

		}
		if ($this->over_cache_time('airowm')> 0 || $force) {
			$this->rebuild_cache_airowm();
		}
		if ($this->over_cache_time('wgov')> 0 || $force) {
				$this->rebuild_cache_wgov();
		}
		if ($this->over_cache_time('airnow')> 0 || $force) {
				$this->rebuild_cache_airnow();
		}
		if ($this->over_cache_time('galerts')> 0 || $force) {
				$this->rebuild_cache_galerts();
		}

			#	$this -> rebuild_properties('jr');
Log::info ("Completed cache refresh cycle");
}
