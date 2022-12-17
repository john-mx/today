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
<div class='border center inlineblock' style=' font-weight:bold; width:40%; vertical-align:top; height:35ex;' >
	<h3><u>Today</u></h3>
	<p><?= $gday[0]['shortForecast'] ?></p>
	<p><?=$gday[0]['highlow']?><br />
	Sunrise: <?= $light['sunrise'] ?> Sunset: <?= $light['sunset'] ?>
	</p>
	<img src="<?=$wgov['jr'][1][0]['icon']?>" ><br />


</div>

<div class='inlineblock' style='width:2em;'></div>

<div class='border center inlineblock' style='font-weight:bold; width:40%; vertical-align:top; height:35ex;'>
	<h3><u>Tonight</u></h3>
	<p><?= $gday[1]['shortForecast'] ?></p>
	<p><?=$gday[1]['highlow']?><br />
	Moonrise: <?= $light['moonrise'] ?>  Moonset: <?= $light['moonset'] ?><br />
	</p>
		<img src="/images/moon/<?= $light['moonpic'] ?>" style='width:76px' ><br />
	<p>
		<?=$light['moonphase'] ?>
	</p>
</div>

</div>



