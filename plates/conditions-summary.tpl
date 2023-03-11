<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



// $conditions = $data['fire'],4data['air'],$data['current'];
//Utilities::echor($data,'data',STOP);


$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0];

//Utilities::echor ($current);

$uvday = <<<EOT
<div class= 'conditions'>
	<b>UV: </b>{$uv['uv']}
	<span style="background-color:{$uv['uvcolor']};">
				 {$uv['uvscale']}
			</span>
			<br />
				&mdash; <i> {$uv['uvwarn']}</i>

</div>
EOT;

$aircolor = $air['jr']['aqi_color'];
$aqday = <<<EOT
<div class= 'conditions'>
		<b>Air Quality:</b> {$air['jr']['aqi']}
			<span style="background-color:$aircolor;">
			 {$air['jr']['aqi_scale']}</span>
			<br />
			&mdash; <i>{$air['jr']['airwarn']}</i>

</div>
EOT;

$windday = <<<EOT
<div class= 'conditions'>
<b>Wind</b> up to {$wday['maxwind']} mph ({$wday['maxwindM']} kph)
	<br />
	&mdash; <i>higher gusts possible</i>

</div>
EOT;

$fireday = <<<EOT
<div class= 'conditions'>
<b>Fire Danger: </b> {$fire['level']}

</div>
EOT;


//Utilities::echor($current,'curr',STOP);
$current_asof = date('g:i a',$current['updatets']);
$currentday = <<<EOT
<div class= 'conditions'>
<b>Temperature: </b>
		{$current['temp_f']}  &deg;F ( {$current['temp_c']} &deg;C)

		</div>
EOT;

$currentwind = <<<EOT
<div class='conditions'>
<b>Wind:</b>
		{$current['wind_mph']} mph ( {$current['wind_kph']} kph) {$current['wind_direction']}

</div>
EOT;
?>




<div class='clearafter'>
	<?=$fireday?>
	<?=$aqday?>
	<?=$uvday?>
</div>
<div class='clearafter'>
	<b>Near Hidden Valley at <?=$current_asof?>: </b><br />
	<?=$currentday?>
	<?=$currentwind?>
</div>
