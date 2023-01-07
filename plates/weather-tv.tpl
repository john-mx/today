<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php
// chooses wapi or wgov data depending on date of wgov

	$wspec=[
	'wslocs'=> $wslocs ??= ['jr'],
	'wsdays'=> $wsdays ??= 3,
	'fcstart'=> $fcstart ??= 0,
	];

//u\echor($wspec);

//u\echor($wapi,'wapi',STOP);
	if(1
	&& isset($wgov['update'])
	&& ($wgovupdate = ($wgov['update']))
	&&( (time() - $wgovupdate) < 8*60*60)
	) {#use wgov
		echo $this->insert('weather-wgov-tv',$wspec);
	} elseif (1 #use wapi
	&& isset($wapi['update'])
	&& ($wapiupdate = $wapi['update'])
	&&( (time() - $wapiupdate) < 8*60*60)
	) {
		echo $this->insert('weather-wapi',$wspec);
	} else { #no good datea
		echo "Cannot build weather data.  All forecasts are stale.";
	}

