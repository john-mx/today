<?php
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
// $conditions = $data['fire'],4data['air'],$data['current'];
//u\echor($data,'data',STOP);

if (! $light || ! $air ){
	echo "No Data Available (conditions)";
	return;
}
$uv = $light['uv'] ?? [];

$air = $air;
$gday = $wgov['jr'][1] ?? [];
$wday = $wapi['forecast']['jr'][0] ?? [];

//u\echor ($current);

$uvday = <<<EOT
<div class= 'conditions'>
	<b>UV: </b>${uv['uv']}
	<span style="background-color:${uv['uvcolor']};">
				 ${uv['uvscale']}
			</span>
			<br />
				&mdash; <i> ${uv['uvwarn']}</i>
			<br />
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
			<br />
</div>
EOT;

$windday = <<<EOT
<div class= 'conditions'>
<b>Wind</b> up to ${wday['maxwind']} mph (${wday['maxwindM']} kph)
	<br />
	&mdash; <i>higher gusts possible</i>
	<br />
</div>
EOT;

$fireday = <<<EOT
<div class= 'conditions'>
<b>Fire Danger: </b> {$admin['fire']['level']}
<br />
</div>
EOT;


//u\echor($current,'curr',STOP);
$current_asof = date('g:i a',$current['updatets']);
$currentday = <<<EOT
<div class= 'conditions'>
<b>Temperature: </b>
		${current['temp_f']}  &deg;F ( ${current['temp_c']} &deg;C)

		</div>
EOT;

$currentwind = <<<EOT
<div class='conditions'>
<b>Wind:</b>
		${current['wind_mph']} mph ( ${current['wind_kph']} kph) ${current['wind_direction']}

</div>
EOT;
?>



<h4>Park Conditions</h4>
<div >
<div class='clearafter left '>

	<?=$fireday?>


	<?=$aqday?>
	<?=$uvday?>
</div>
<div class='clearafter  left '>

	<h5>Near Hidden Valley at <?=$current_asof?>: </h5>
	<?=$currentday?>

	<?=$currentwind?>


</div>
<div class='clear'></div>
</div>
