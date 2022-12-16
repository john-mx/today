<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;


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
	UV: ${uv['uv']}
			<div class='inlineblock' style="padding-left:2em;padding-right:2em;background-color:${uv['uvcolor']};">
				 ${uv['uvscale']}
			</div>
				&mdash; ${uv['uvwarn']}
			<br />
EOT;
		$aircolor = $air['jr']['aqi_color'];
$aqday = <<<EOT
		Air Quality:
			{$air['jr']['aqi']}
			<div class='inlineblock' style="padding-left:2em;padding-right:2em;background-color:$aircolor;">
			 {$air['jr']['aqi_scale']}
				</div>
				&mdash; {$air['jr']['airwarn']}
			<br />
EOT;

$gday = $wgov['jr'][1]; #first day
if (!isset($gday[1])){ #no day, only night;
	$gday[1] = $gday[0];
	$gday[0] = [];
}
$wday = $wapi['forecast']['jr'][0];


?>

<?php if ($data['pithy']): ?>
	<p class='center'><i><?=$data['pithy']?></i></p>
<?php endif; ?>

<table style='width:100%;font-size:1.2rem;' >
<tr class='no-bottom' >
<td class='width50 center;'>
	<h3><u>Today</u></h3>
	<?php if ($gday[0] ): ?>
	<p><b><?= $gday[0]['shortForecast'] ?></b></p>
	<img src="<?=$wgov['jr'][1][0]['icon']?>" >

	<?php endif; ?>
</td><td class='width50 center;'>

	<h3><u>Tonight</u></h3>
	<p ><b><?= $gday[1]['shortForecast'] ?></b></p>
	<img src="/images/moon/<?= $light['moonpic'] ?>" style='width:76px' ><br />
	<?=$light['moonphase'] ?>

</td>
</tr>
<tr class='no-top'><td>
<?php if ($gday[0] ): ?>
	<p  style='font-size:1rem;'>
		<?=$gday[0]['highlow']?><br />
		Sunrise: <?= $light['sunrise'] ?> Sunset: <?= $light['sunset'] ?>
	</p>
<?php else: ?>
	N/A
<?php endif; ?>
</td><td>

	<p  style='font-size:1rem;'>
	<?=$gday[1]['highlow']?><br />
	Moonrise: <?= $light['moonrise'] ?>  Moonset: <?= $light['moonset'] ?><br />
	</p>
</td></tr>


</table>
<h4>Conditions</h4>

						<?=$aqday ?>
						<?=$uvday ?>
					Wind: up to <?= $wday['maxwind']?> mph (<?= $wday['maxwindM'] ?> kph) (higher gusts possible) <br>

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


					<b>Wind: </b> up to <?= $wday['maxwind']?> mph (<?= $wday['maxwindM'] ?> kph) (higher gusts possible) <br>
				</div>
				<div class='float'>
				<b>Night: </b> <?= $gday[1]['shortForecast'] ?>.
						<?=$gday[1]['highlow']?><br />
				</div>
				<div class='clear'></div>

			<?php endfor; //end day ?>
<hr>
<?php
	$this->insert('notices',['notices' => $data['notices']]);
?>
<hr>
<?php
	$this->insert('advice',['advice' => $data['advice']]);
?>
	<p><hr>
<small>Weather.gov updated at <?=$gupdated ?> <br />
Wapi updated at <?=$wupdated?></small>
</p>


