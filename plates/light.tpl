<?php
use DigitalMx as u;

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
$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0];

//u\echor($gday,'gday',NOSTOP);

?>


<div class ='center' style='vertical-align:top' >

	<div class='border center inlineblock' style=' font-weight:bold; width:40%; vertical-align:top; height:24ex;' >
	<h3><u>Today</u></h3>
	<?php $i=1; if (!isset($gday[$i])): $i=0; ?>
		The Day is Done!
	<?php else: ?>
		<div class=' inlineblock center' style='vertical-align:top; width:40%;'>
		<img src="<?= $wgov['jr'][1][0]['icon'] ?>" class='auto' ><br />
		<?= $gday[0]['shortForecast'] ?>
		</div >
		<div class=' center inlineblock' style='float:right;vertical-align:top;width:60%'>

		<p style='margin-top:0'><?=$gday[0]['highlow']?><br />
		<br />
		Sunrise: <?= $light['sunrise'] ?> Sunset: <?= $light['sunset'] ?>
		</p>
		</div>
		<div class='clear'></div>

	<?php endif; ?>
	</div>

	<div class='inlineblock' style='width:2em;'></div>

	<div class='border center inlineblock' style=' font-weight:bold; width:40%; vertical-align:top; height:24ex;' >

	<h3><u>Tonight</u></h3>
		<div class=' inlineblock center' style='vertical-align:top;width:40%;'>
		<img src="/images/moon/<?= $light['moonpic'] ?>" style='width:76px' class='auto' ><br />
	<?= $gday[$i]['shortForecast'] ?>
		</div>
		<div class=' center inlineblock' style='float:right;vertical-align:top;width:60%'>
	<p style='margin-top:0' ><?=$gday[$i]['highlow']?><br />
	<br />
	Moonrise: <?= $light['moonrise'] ?>  Moonset: <?= $light['moonset'] ?><br />
	<?=$light['moonphase'] ?><br />
	</p>
		</div>

		<br />
		<div class='clear'></div>
	</div>

</div>





