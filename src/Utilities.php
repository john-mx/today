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

	public static function showHelp($ref) {
		$t = <<<EOT
		<div class='inlineblock'><img src='/images/help.png'  style = 'width:24px;' onClick="helpwin('$ref');" /></div>
	EOT;
		echo $t;
	}

function echop($text){
    echo "<p>$text</p>";
}

function echopre($text){
    echo "<pre>\n$text\n</pre>\n";
}

function echoc($text,$title=''){
// echo block of code
    echo "<div class='code'>";
    if ($title) echo "<u>$title:</u><br><br>";
    echo nl2br($text) ;
    echo "</div>" . NL;
}


function echot(string $string,$title='',$stop=false ){
	// call with some php var and optional title for the var.
	// will print out the contents of var and the location in code this function was called from.

	// get the caller
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$ref =  basename($caller['file']) . ' (' . $caller['line'] . ')';

	$title = "Tracer ($ref)";
   echo "<h4>$title</h4>";
   echo "<pre>$string</pre>\n";
   if ($stop) exit;
}


public static function echor($var,$title='',$stop=false){
	// call with some php var and optional title for the var.
	// will print out the contents of var and the location in code this function was called from.

	// get the caller
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$ref =  basename($caller['file']) . ' (' . $caller['line'] . ')';

	$title = "$title ($ref)";
   echo "<h4>$title</h4>";
   echo "<pre>" .  print_r($var,true) . "</pre>\n";
   if ($stop) exit;
}

public static function txt2html($text){
	// returns text coverting line feeds to <br>s and entities
	$text = htmlspecialchars($text,ENT_QUOTES);
	$text = nl2br($text);
	return $text;
}

public static function special($var){
    #convert < > " & , but not ' (default ENT_COMPAT)
	return htmlspecialchars($var,ENT_QUOTES);
}
public static function despecial($var) {
	return htmlspecialchars_decode($var);
}


}
