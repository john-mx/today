<?php
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
// $conditions = $data['fire'],4data['air'],$data['current'];
//u\echor($data,'data',STOP);

$uv = $light['uv'];

$air = $air;
$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0];
$current = $wapi['current'];

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
<b>Fire Danger: </b> ${fire['level']}
<br />
</div>
EOT;

$currenttempdt = date('g:i a',$current['last_updated_epoch']);
$currentday = <<<EOT
<div class= 'conditions'>
<b>Temperature: </b> <small>at Jumbo Rock</small><br />
	&nbsp;&nbsp;&nbsp;
		${current['temp_f']}  &deg;F ( ${current['temp_c']} &deg;C) at $currenttempdt
		<br>
		</div>
EOT;

?>



<h4>Park Conditions</h4>
<div class='center'>

	<?=$fireday?>


	<?=$aqday?>
	<?=$uvday?>

	<?=$windday?>



</div>
<div class='clear'></div>
