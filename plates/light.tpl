<?php
use DigitalMx as u;

$gupdated = '';
$wupdated = '';
if(empty($wgov)): echo "<p>No wgov Data</p>"; exit;
	else:
	$gupdated =  date('M d g:i a',$wgov['update']);
	endif;

if(empty($wapi)): echo "<p>No wapi Data</p>"; exit;
	else:
	$wupdated =  date('M d g:i a',$wapi['update']);
	endif;

$light = $light['light'];
$uv = $light['uv'];
$air = $air;
$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0];

//u\echor($gday,'gday',NOSTOP);

?>
<div class ='center clearafter flex-container' style='vertical-align:top' >

	<div class='border center floatl' style=' font-weight:bold; width:48%; vertical-align:top; ' >
		<h3><u>Today</u></h3>
		<?php $i=1; if (!isset($gday[$i])): $i=0;
		?>
			The Day is Done!
		<?php else: ?>
		<div class=' inlineblock center' style='vertical-align:top; width:40%;'>
			<img src="<?= $wgov['jr'][1][0]['icon'] ?>" class='auto' ><br />
			<?= $gday[0]['shortForecast'] ?>
		</div >

		<div class=' center inlineblock' style='vertical-align:top;width:55%'>
			<p style='margin-top:0;font-size:1.2rem;'><?=$gday[0]['highlow']?></p>
			<p>
			Sunrise:&nbsp;<?= $light['sunrise'] ?> Sunset:&nbsp;<?= $light['sunset'] ?>
			</p>

			<p><b>Wind: </b> up to <?= $wday['maxwind']?>&nbsp;mph (<?= $wday['maxwindM'] ?>&nbsp;kph) </p>
		</div>
		<?php endif; ?>
	</div>

	<div class='border center floatr' style=' font-weight:bold; width:45%; vertical-align:top;' >
		<h3><u>Tonight</u></h3>
		<div class=' inlineblock center' style='vertical-align:top;width:40%;'>
			<img src="/images/moon/<?= $light['moonpic'] ?>" style='width:76px' class='auto' >
			<br />
			<?= $gday[$i]['shortForecast'] ?>
			</div>
			<div class=' center inlineblock' style='vertical-align:top;width:55%'>
				<p style='margin-top:0;font-size:1.2rem;' ><?=$gday[$i]['highlow']?></p>
				<p>Moonrise:&nbsp;<?= $light['moonrise'] ?>  Moonset:&nbsp;<?= $light['moonset'] ?><br /><br />
				<?=$light['moonphase'] ?> (<?=$light['moonillumination']?>%&nbsp;illum)
				</p>
			</div>
		</div>
	</div>




