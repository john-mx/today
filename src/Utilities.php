<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START
	require_once  './init.php';

	use DigitalMx\jotr\Definitions as Defs;



class Utilities {

public function __construct () {
	echo "He3re";
}

public function over_cache_time($section) {
	//global $Defs;
	/* dies if file not exists
		0 if mtime is under the limit
		diff if mtime is over the limit by diff
	*/

	if (!file_exists(CACHE[$section])){ die ("No cache file for $section");}

	$filetime = filemtime (CACHE[$section]);
	$limit = Defs->getMaxTime($section);
	$diff = time() - $filetime;
	if ($limit && ($diff > $limit)) return $limit;
//	echo "$section: limit $limit; diff $diff;" . BR;
	return 0;
}


}
