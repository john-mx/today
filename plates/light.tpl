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
$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0];


?>


<div class ='center' style='vertical-align:top' >

	<div class='border center inlineblock' style=' font-weight:bold; width:40%; vertical-align:top; height:22ex;' >
	<h3><u>Today</u></h3>
		<div class=' inlineblock center' style='vertical-align:top;'>
		<img src="<?= $wgov['jr'][1][0]['icon'] ?>" class='auto' ><br />
		<?= $gday[0]['shortForecast'] ?>
		</div >
		<div class=' center inlineblock' style='float:right;vertical-align:top;'>

		<p><?=$gday[0]['highlow']?><br />
		<br />
		Sunrise: <?= $light['sunrise'] ?> Sunset: <?= $light['sunset'] ?>
		</p>
		</div>
		<div class='clear'></div>

	</div>

	<div class='inlineblock' style='width:2em;'></div>

	<div class='border center inlineblock' style='font-weight:bold; width:40%; vertical-align:top; height:22ex;'>
	<h3><u>Tonight</u></h3>
		<div class=' inlineblock center' style='vertical-align:top;'>
		<img src="/images/moon/<?= $light['moonpic'] ?>" style='width:76px' class='auto' ><br />
	<?= $gday[1]['shortForecast'] ?>
		</div>
		<div class=' center inlineblock' style='float:right;vertical-align:top;'>
	<p><?=$gday[1]['highlow']?><br />
	<br />
	Moonrise: <?= $light['moonrise'] ?>  Moonset: <?= $light['moonset'] ?><br />
	<?=$light['moonphase'] ?><br />
	</p>
		</div>
	<div class='clear'></div>
		<br />
	</div>

</div>





