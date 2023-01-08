<?php
use DigitalMx as u;


if (!$light ){
	echo "No Data Available (light)";
	return;
}
//u\echor($light,'light in tpl');

// get today's daily data
// if (! $day = $light['gday'] ?? []):
// 	echo "No data available (weather.gov day)";
// 	return;
// endif;


$day = $light['day'];
$night = $light['night'];
$updated = date('M d g:i a',$light['update']['ts']);
?>

<div class ='center clearafter flex-container' style='vertical-align:top' >

	<div class='border center floatl' style=' font-weight:bold; width:48%; vertical-align:top; ' >
		<h3><u>Today</u></h3>

		<div class=' inlineblock center' style='vertical-align:top; width:40%;'>
			<img src="<?= $day['icon'] ?>" class='auto' ><br />
			<?= $day['short'] ?><br />
			<b>Wind: </b> <?= $day['wind']?>
		</div >

		<div class=' center inlineblock' style='vertical-align:top;width:55%'>
			<p style='margin-top:0;font-size:1.5rem;'> <?=$day['high']?></p>
			<p>
			Sunrise:&nbsp;<?= $day['sunrise'] ?> Sunset:&nbsp;<?= $day['sunset'] ?>
			</p>
		</div>

	</div> <!-- end today -->


	<div class='border center floatr  bg-blue ' style=' font-weight:bold; width:45%; vertical-align:top;' >
		<h3><u>Tonight</u></h3>

		<div class=' inlineblock center' style='vertical-align:top;width:40%;'>
			<img src="/images/moon/<?= $night['icon'] ?>" style='width:76px' class='auto' >
			<br />
							<?=$night['moonphase'] ?> (<?=$night['moonillum']?>%&nbsp;illum)

		</div>
		<div class=' center inlineblock' style='vertical-align:top;width:55%'>
				<p style='margin-top:0;font-size:1.5rem;'>
			<?=$night['low']?>
			</p>
				<p>Moonrise:&nbsp;<?= $night['moonrise'] ?>  Moonset:&nbsp;<?= $night['moonset'] ?><br /><br />

				</p>
		</div>
	</div> <!-- end tonight -->


	</div> <!-- end container -->
	<div class='inleft2 left'><small><?=$light['update']['source']?>, updated at <?=$updated?> </small></div>



