<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

//supply this with weather['wapi']
?>

<?php
$gupdated = '';
$wupdated = '';
if(empty($wgov = $data['wgov'])): echo "<p>No wgov Data</p>"; exit;
	else:
	$gupdated =  date('M d g:i a',$wgov['update']);
	endif;

if(empty($wapi=$data['wapi'])): echo "<p>No wapi Data</p>"; exit;
	else:
	$wupdated =  date('M d g:i a',$wapi['update']);
	endif;
$light = $data['light']['light'];
$uv = $data['light']['uv'];
$air = $data['air'];
$uvday = <<<EOT
	<b>UV: </b>${uv['uv']}
			<div class='inlineblock' style="padding-left:2em;padding-right:2em;background-color:${uv['uvcolor']};">
				 ${uv['uvscale']}
			</div>
				&mdash; ${uv['uvwarn']}
			<br />
EOT;
		$aircolor = $air['jr']['aqi_color'];
$aqday = <<<EOT
		<b>Air Quality: </b>
			{$air['jr']['aqi']}
			<div class='inlineblock' style="padding-left:2em;padding-right:2em;background-color:$aircolor;">
			 {$air['jr']['aqi_scale']}
				</div>
				&mdash; {$air['jr']['airwarn']}
			<br />
EOT;
$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0];
?>


<table style='width:100%' >
<tr class='no-bottom' ><td >
	<p  style='font-size:1.6em;'>
	<b><?= $gday[0]['shortForecast'] ?></b>.<br />
	<b><?=$gday[0]['highlow']?></b><br />
	<b>Sunrise: </b><?= $light['sunrise'] ?> <b>Sunset: </b><?= $light['sunset'] ?><br />
	</p>
</td><td>

	<p style='font-size:1.6em;'>
	<b><?= $gday[1]['shortForecast'] ?></b>.<br />
	<b><?=$gday[1]['highlow']?></b><br />
	<b>Moonrise: </b> <?= $light['moonrise'] ?>  <b>Moonset: </b><?= $light['moonset'] ?><br />
	</p>
</td>
</tr>

<tr class='no-bottom no-top'>
<td class='center'>
<img src="<?=$wgov['jr'][1][0]['icon']?>" >
</td>
<td align='center'>
	<img src="/images/moon/<?= $light['moonpic'] ?>" style='width:76px' ><br />
	<?=$light['moonphase'] ?>

</td>
</tr>
<tr class='no-top'><td  class='left' style='font-size:1.2em;'>
						<?=$aqday ?>
						<?=$uvday ?>
					<b>Wind up to: </b> <?= $wday['maxwind']?> mph (<?= $wday['maxwindM'] ?> kph) (higher gusts possible) <br>
</td><td  class='left'  style='font-size:1.2em;'>
 <br />

</td></tr>
</table>
<?php
	$this->insert('notices',['notices' => $data['notices']]);
?>

<hr>
<h4>Forecast</h4>
		<?php

			for ($i=2;$i<3;++$i) : //for 2 days
				$gday = $wgov['jr'][$i];
				$wday = $wapi['forecast']['jr'][$i-1];
		?>

				<b><u><?= $wday['date'] ?></u></b><br />
				<div class='inleft2 float width45'>

					<b>Day: </b> <?= $gday[0]['shortForecast'] ?>.
						<?=$gday[0]['highlow']?><br />
						<?php if ($i==1) : ?>
						<?=$aqday ?>
						<?=$uvday ?>
						<?php endif; ?>


					<b>Wind up to: </b> <?= $wday['maxwind']?> mph (<?= $wday['maxwindM'] ?> kph) (higher gusts possible) <br>
				</div>
				<div class='float'>
				<b>Night: </b> <?= $gday[1]['shortForecast'] ?>.
						<?=$gday[1]['highlow']?><br />
				</div>
				<div class='clear'></div>

			<?php endfor; //end day ?>
<hr>
<?php
	$this->insert('advice',['advice' => $data['advice']]);
?>
	<p><hr>
<small>Weather.gov updated at <?=$gupdated ?> <br />
Wapi updated at <?=$wupdated?></small>
</p>


