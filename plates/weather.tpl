<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


?>

<?php
/*
	chooses wapi or wgov data depending on date of wgov

	starting array wspec:
	wslocs = locations to report on
	wsdays = number of days to report on
	wsstart = days ahead to start forcast.

	*/

	$wspec=[
	'wslocs'=> $wslocs ??= ['jr','br','cw'],
	'wsdays'=> $wsdays ??= 3,
	'wsstart'=> $wsstart ??= 0,
	];


//Utilities::echor($wspec);

//Utilities::echor($wapi,'wapi',STOP);
	if(1
	&& isset($wgov['update'])
	&& ($wgovupdate = ($wgov['update']))
	&&( (time() - $wgovupdate) < 8*60*60)
	) {#use wgov
		echo $this->insert('weather-wgov',$wspec);
	} elseif (1 #use wapi
	&& isset($wapi['update'])
	&& ($wapiupdate = $wapi['update'])
	&&( (time() - $wapiupdate) < 8*60*60)
	) {
		echo $this->insert('weather-wapi',$wspec);
	} else { #no good datea
		echo "Cannot build weather data.  All forecasts are stale.";
	}

