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
<div >
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
<div  >
		<b>Air Quality:</b> {$air['jr']['aqi']}
			<span style="background-color:$aircolor;">
			 {$air['jr']['aqi_scale']}</span>
			<br />
			&mdash; <i>{$air['jr']['airwarn']}</i>
			<br />
</div>
EOT;

$windday = <<<EOT
<div >
<b>Wind</b> up to ${wday['maxwind']} mph (${wday['maxwindM']} kph)
	<br />
	&mdash; <i>higher gusts possible</i>
	<br />
</div>
EOT;

$fireday = <<<EOT
<div >
<b>Fire Danger: </b> {$admin['fire']['level']}
<br />
</div>
EOT;


//Utilities::echor($current,'curr',STOP);
$current_asof = date('g:i a',$current['updatets']);
$temp = <<<EOT
<div >
<b>Temperature: </b>
		${current['temp_f']}&deg;F ( ${current['temp_c']}&deg;C)

		</div>
EOT;

$currentwind = <<<EOT
<div>
<b>Wind:</b>
		${current['wind_mph']} mph ( ${current['wind_kph']} kph) ${current['wind_direction']}

</div>
EOT;
?>




<div class='center clearafter  border' style='font-weight:600;'>
<h3>Park Conditions  at <?=$current_asof?></h3>
	<div class=' floatl' style='width:30%'>
	<?=$temp?>
	<?=$currentwind?>

	<?=$fireday?>
	</div>

	<div class='floatl ' style='width:30%'>
	<?=$aqday?>
	</div>
	<div class='floatl ' style='width:30%'>
	<?=$uvday?>
	</div>

</div>



