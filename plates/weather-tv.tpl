<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


?>

<?php
// chooses wapi or wgov data depending on date of wgov

	$wspec=[
	'wslocs'=> $wslocs ??= ['jr'],
	'wsdays'=> $wsdays ??= 3,
	'wsstart'=> $wsstart ??= 0,
	];

//Utilities::echor($wspec);

//U::echor($wgov,'wgov',STOP);
//echo "up: " .$wgov['update'] . ' time ' . time() . BR; exit;
	if(1
	&& isset($wgov['update'])
	&& ($wgovupdate = ($wgov['update']))
	&&( (time() - $wgovupdate) < 24*60*60)
	) {#use wgov
		echo $this->insert('weather-wgov-tv',$wspec);

	} elseif (0 #use wapi

	&& isset($wapi['update'])
	&& ($wapiupdate = $wapi['update'])
	&&( (time() - $wapiupdate) < 8*60*60)
	) {
		echo $this->insert('weather-wapi',$wspec);
	} else { #no good datea
		echo "Cannot build weather data.  All forecasts are stale.";
	}

