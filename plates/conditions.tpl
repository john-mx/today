<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



// $conditions = $data['fire'],4data['air'],$data['current'];
//Utilities::echor($data,'data',STOP);




$air = $air;
$gday = $wgov['jr'][1] ?? [];
$wday = $wapi['forecast']['jr'][0] ?? [];

//Utilities::echor ($current);

$uvday = <<<EOT
<div  style='margin-bottom:6px;'>
	<b>UV: </b>{$current['uv']['uv']}
	<span style="background-color:{$current['uv']['uvcolor']};">
				 {$current['uv']['uvscale']}
			</span>
			<br />
				&mdash; <i> {$current['uv']['uvwarn']}</i>
			<br />
</div>
EOT;

$aircolor = $air['jr']['aqi_color'];
$aqday = <<<EOT
<div   style='margin-bottom:6px;'>
		<b>Air Quality:</b> {$air['jr']['aqi']}
			<span style="background-color:$aircolor;">
			 {$air['jr']['aqi_scale']}</span>
			<br />
			&mdash; <i>{$air['jr']['airwarn']}</i>
			<br />
</div>
EOT;



$fireday = <<<EOT
<div style='margin-bottom:6px;'>
<b>Fire Danger: </b> {$admin['fire']['level']}
<br />
</div>
EOT;


//Utilities::echor($current,'curr',STOP);
$current_asof = date('g:i a',$current['updatets']);
$temp = <<<EOT
<div  style='margin-bottom:6px;'>
<b>Temperature: </b>
		{$current['temp_f']}&deg;F ( {$current['temp_c']}&deg;C)
</div>
EOT;

$currentWind = <<<EOT
<div style='margin-bottom:6px;'>
<b>Wind:</b>
		{$current['wind_mph']} mph ( {$current['wind_kph']} kph) {$current['wind_direction']} <br />
		Gusts to {$current['gusts_mph']} mph<br />
		Wind chill: {$current['wind_chillF']}&deg;F ({$current['wind_chillC']}&deg;C)

</div>
EOT;

$currentHumidity = <<<EOT
<div style='margin-bottom:6px;'>
<b>Humidity:</b>
	{$current['humidity']} %
</div>
EOT;

?>


<div class='center clearafter  border' id='conditions'>
<h3 style='margin-bottom:0px;text-align:center;'>Park Conditions  at <?=$current_asof?></h3>
<div style='font-weight:normal;font-size:1rem; margin-bottom:0.8em;'>Reported near Hidden Valley</div>

	<div class=' floatl' style='width:30%'>
	<?=$temp?>
	<?=$currentWind?>

	</div>

	<div class='floatl ' style='width:30%'>
	<?=$aqday?>

		<?=$fireday?>
		<?=$currentHumidity?>

	</div>

<div class='floatl ' style='width:30%'>
		<?=$uvday?>
	</div>
</div>



