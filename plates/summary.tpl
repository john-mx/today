<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;


?>
<style>
body {
	font-size:1.1rem;
}


</style>


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

<?php $this->insert('light',['data' => $data]); ?>

<!--
<h4>Today's Conditions</h4>
<?php $this->insert('conditions-summary',$data); ?>

 -->
<h4>Tomorrow</h4>
		<?php

			for ($i=2;$i<3;++$i) : //for 2 days
				$gday = $wgov['jr'][$i];
				$wday = $wapi['forecast']['jr'][$i-1];
		?>


				<div class='inleft2  '>

					<b>Day: </b> <?= $gday[0]['shortForecast'] ?>.
						<?=$gday[0]['highlow']?><br />
						<?php if ($i==1) : ?>
						<?=$aqday ?>
						<?=$uvday ?>
						<?php endif; ?>


				<b>Wind: </b> up to <?= $wday['maxwind']?> mph (<?= $wday['maxwindM'] ?> kph) (higher gusts possible) <br>

				<b>Night: </b> <?= $gday[1]['shortForecast'] ?>.
						<?=$gday[1]['highlow']?><br />

			</div>
			<?php endfor; //end day ?>


<?php
	$this->insert('notices',['notices' => $data['notices']]);
?>

<h4>Today's Events</h4>
<?php
if(empty($calendar = $data['calendar'])) : echo "<p class='inleft2'>No Events Scheduled</p>"; else:
//u\echor($calendar,'data-calendar',NOSTOP);
?>


<?php foreach ($calendar as $cal) :
		if ($cal['suspended']){continue;} // dont display
		$eventdate = date('l, F j',$cal['dt']);
		$eventtime = date('g:i a', $cal['dt']);
	?>
	<div class='inleft2'>
	<b><?=$eventtime?> </b>:
	<b><?=$cal['title']?></b>
 	(<?=$cal['duration']?>)
  at <?=$cal['location']?>
</div>

<?php endforeach; ?>

<?php endif; ?>

<?php $this->insert('end'); ?>
