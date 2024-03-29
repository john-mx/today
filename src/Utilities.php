<?php
namespace DigitalMx\jotr;

#ini_set('display_errors', 1);

//BEGIN START


	use DigitalMx\jotr\Definitions as Defs;



class Utilities {

	public function __construct () {
		echo "He3re";
	}



public static function showHelp($ref) {
		$t = <<<EOT
		<div class='inlineblock'><img src='/images/help.png'  style = 'width:24px;' onClick="helpwin('$ref');" /></div>
EOT;
		echo $t;
}

public static function echop($text){
    echo "<p>$text</p>";
}

public static function echopre($text){
    echo "<pre>\n$text\n</pre>\n";
}

public static function echoc($text,$title=''){
// echo block of code
    echo "<div class='code'>";
    if ($title) echo "<u>$title:</u><br><br>";
    echo nl2br($text) ;
    echo "</div>" . NL;
}


public static function echot(string $string,$title='',$stop=false ){
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
    #convert < > " & , but not ' ( ENT_COMPAT)
    # also convert ' (ENT_QUOTES - default);
	return htmlspecialchars($var);
}
public static function despecial($var) {
	return htmlspecialchars_decode($var);
}
public static function echoAlert($msg) {
	$t = "$msg";

	echo "<script>
	alert(\"$t\") </script>";

}
public static function alertBadInput ($msg) {
	$t = "Bad Input. $msg";

	echo "<script>
		alert(\"$t\");
		window.history.go(-1);
		</script>
		";
		exit;
}
public static function listRecent($number,$path){
/**
	#returns a list of n most recent files of type in directory.

**/
	$latest_ctime = 0;
	$mods = array(); $fnames=array(); $files=array();

	if (is_dir($path) == false){return [];}

    foreach (glob($path . '/*') as $f) {
        $mtimes[filemtime($f)] = $f;
    }
    krsort($mtimes);
    $files = array_values(array_slice($mtimes, 0, $number, true));
    $fnames = array_filter($files,function($f){return basename($f);});
    return $fnames;
}

public static function format_time($time){

 		if (empty($time)) self::alertBadInput('EMPTY');
 		$etime = " ('$time')";
		preg_match('/([\d\:]+)\s*([apm]{2})/i',$time,$m);
		//self::echor($m);
		if (empty($m[0])) self::alertBadInput ('NOT TIME ? ' . $etime);
		@list($hrs,$mins) = explode (':',$m[1],2);
		if ($hrs < 1 || $hrs > 12) self::alertBadInput ('HOURS OOB: ' . $hrs  . $etime);
		if (empty($mins))$mins = '0';
		if ($mins<0 || $mins >59) self::alertBadInput ('MINS OOB: ' . $mins  . $etime);
		$ap = strtolower($m[2]);
		if ($ap != 'am' && $ap != 'pm') self::alertBadInput ('AMPM: '. $ap . $etime);
		$formatted = sprintf('%2d:%02d %s',$hrs,$mins,$ap);


		return $formatted;


}
public static function buildOptions($val_array,$check='',$choose = true){
	// val array is keys and values, check is current selection,
	// choose is true to include the Choose One... line
	$opt = '';
	if ($choose) {
		$opt = "<option value=''>Choose One...</option>";
	}


	if( self::isAssociative($val_array)){
        foreach ($val_array as $k => $v){
            $checked = ($k == $check)?"selected":'';
            $opt .= "<option value='$k' $checked>$v</option>";
        }
    }
    # or if one-dimensional array
    else {
        foreach ($val_array as $k){
            $checked = ($k == $check)?"selected":'';
            $opt .= "<option value='$k' $checked>$k</option>";
        }
    }

	#echo "check: $check.  options:", $opt,"<br>";
	return $opt;
}


public static function isAssociative($arr)
{
    return array_keys($arr) !== range(0, count($arr) - 1);
}

public static function buildCheckBoxSet(
    $var_name,
    $val_array,
    $check = '',
    $per_row = 1,
    $show_code = false
) {
    // like building select options, but shows as
    // checkboxes instead (multiples ok)
    // $check is string with multiple characters to match against the val array
    //per_row is how many items to put in a row; 1 is verticle list
        $opt = '';

    $rowcount = 0;
    asort($val_array);
    $varcount = count($val_array);
    $opt = '';

    foreach ($val_array as $k => $v) {
    #echo "k=$k,v=$v,check=$check" . BRNL;
        if (empty($v)){continue;}

        $label = $v;
        $label .= ($show_code)? " ($k)" : '';

          $checkme = (strstr($check, (string)$k))?"checked":'';
          $opt .= "<div style='white-space:nowrap;display:inline'> <label><input type='checkbox' name='{$var_name}[]' value='$k' $checkme>$label</label></div> ";
          ++$rowcount;
    }
      return $opt;
}

public static function buildRadioSet(
    $var_name,
    $val_array,
    $check = '',
    $cols = 1,
    $show_code = false
) {
    // like building select options, but shows as
    // checkboxes instead (multiples ok)
    // $check is string with multiple characters to match against the val array
    //cols is how many cols to form.  1 makes a verticel column;
    // n makes a horizontal row.
        $opt = '';

    $rowcount = 0;
    $tablestyle=false;
    asort($val_array);
    $varcount = count($val_array);

    $opt = '';

    foreach ($val_array as $k => $v) {
    #echo "k=$k,v=$v,check=$check" . BRNL;
        if (empty($v)){continue;} #removes blank values
        $label = $v;
        if ($show_code) $label .= " ($k)";

          $checkme = ($check == $k)?"checked":'';
          $name = $var_name;
          $opt .= "<div style='white-space:nowrap;display_inline;'><label><input type='radio' name='$name' value='$k' $checkme>&nbsp; $label</</label></div> ";
      }
      return $opt;
}

public static function element_sort(array $array, string $on, $order=SORT_ASC)
{
	/* copied from php manual.
		 sorts a list of arrays by one of the elemnts
		array (
			123 => array (
				'name' => 'asdfl',
				...
			124 => ...

		$sorted = element_sort($unsorted, 'name');


	*/

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}
public static function addTimestamp ($file){
	/* adds ?HHmm to file name to force cache updates
		file is relative to public (e.g., /css/main.css)
		*/
	if (substr($file,0,1) !== '/' || ! file_exists(SITE_PATH . $file)){
		die ("illegal file name at addTimeStamp: $file");
	}
	$ts = date('mdHi',filemtime(SITE_PATH . $file));
	$file_ts = $file . '?' . $ts;

	return $file_ts;
}
public static function inMultiArray($element, array $array, bool $strict = true) : bool {
		 static $currentMultiArrayExec = 0;

        $currentMultiArrayExec++;

       // if($currentMultiArrayExec >= ini_get("xdebug.max_nesting_level")) return false;

        foreach($array as $key => $value){
            $bool = $strict ? $element === $key : $element == $key;

            if($bool) return true;

            if(is_array($value)){
                $bool = self::inMultiArray($element, $value, $strict);
            } else {
                $bool = $strict ? $element === $value : $element == $value;
            }

            if($bool) return true;
        }

        $currentMultiArrayExec = 0;
        return isset($bool) ? $bool : false;
 }



public static function humanSecs($esecs){
    // express time in secs in human readable
    if (! $esecs = intval($esecs) ) {
		die ("$esecs is not an integer.");
	}

    $t = '';
    $edays = intval ($esecs/ 86400);
    if ($edays > 0){
        $esecs %= 86400;
        $t .= "$edays days, ";
    }
    $ehrs = intval ($esecs / 3600);
    if ($ehrs > 0) {
        $esecs %=  3600;
        $t .= "$ehrs hours, ";
    }
    $emins = intval ($esecs / 60);
    if ($emins > 0) {
        $esecs %= 60;
        $t .= "$emins minutes,  ";
    }

    $t .= "$esecs seconds.";

    return $t;
}

public static function get_calling_class() {

    //get the trace
    $trace = debug_backtrace();

    // Get the class that is asking for who awoke it
    $class = $trace[1]['class'];

    // +1 to i cos we have to account for calling this function
    for ( $i=1; $i<count( $trace ); $i++ ) {
        if ( isset( $trace[$i] ) ) // is it set?
             if ( $class != $trace[$i]['class'] ) // is it a different class
                 return $trace[$i]['class'];
    }
}

public static function humanSecs2($esecs){
    // express time in secs in human readable
    if (! $esecs = intval($esecs) ) {
		die ("$esecs is not an integer.");
	}

   // $t = "($esecs) ";
   $t = '';
    $days=$hours=$mins=0;
    $edays = intval ($esecs/ 86400);
    if ($edays > 0){
        $esecs %= 86400;
        $days = $edays;
    }
    $ehrs = intval ($esecs / 3600);
    if ($ehrs > 0) {
        $esecs %=  3600;
        $hours = $ehrs;
    }

    $emins = intval ($esecs / 60);
    if ($emins > 0) {
        $esecs %= 60;
        $mins = $emins;
    }

    if ($days) {
    	$t .= "$edays days";
    	if ($hours || $mins){
    			$t .= ', ';
    	}
    }

	if ($mins == '30' && $hours == 1){
		$t .= "90 minutes";
	} elseif ($mins == '30') {
			$hours += '.5';
			$t .= "$hours hours";
	} elseif ($mins && ($hours == 0)) {
		$t .= "$mins minutes";
	} elseif ($mins) {
		$t .= "$hours hours, $mins minutes";
	}else {
		$t .= "$hours hour";
		if ($hours > 1) $t .= 's';

	}

    return $t;
}
public static function days_ago ($date_str) {	//takes a date and returns the age from today in days
	// date_str can be normal string or timestamp.
	// routine converts to timestamp and returns days from now.


	$dt = new \DateTime();
	; #may change
	if (is_numeric($date_str)){
		$t = $date_str;
	} elseif (! $t = strtotime($date_str) ){
		#echo "u\days_ago cannot understand date $date_str";
		$t = 0;
	}

	#is unix time
	$dt->setTimeStamp($t);

	$dtnow = new \DateTime();

	$diff = $dt -> diff($dtnow);
	$diff_str = $diff->format('%a');


	return $diff_str;
}


}
