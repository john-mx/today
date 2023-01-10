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


$day = $light['day'] ?? [];

$night = $light['night'];
$tomorrow = $light['tomorrow'];
$updated = date('M d g:i a',$light['update']['ts']);


?>

<div class =' flex-container  center' >

	<!-- LEFT PANEL -->

	<?php if ($day) : ?>
	<div class='dayblock  '>
		<h3><u>Today</u></h3>

		<div class=' inlineblock center' style='vertical-align:top; width:40%;'>
			<img src="<?= $day['icon'] ?>" style='width:8em;' class='auto' ><br />

			<b>Wind: </b> <?= $day['wind']?>
		</div >

		<div class=' center inlineblock' style='font-weight:bold;'style='vertical-align:top;width:55% width-min:350px;'>
			<p><?= $day['short'] ?></p>
			<p style='margin-top:0;font-size:1.3rem;'> High: <?=$day['high']?></p>
			<p>
			Sunrise:&nbsp;<?= $day['sunrise'] ?> <br />Sunset:&nbsp;<?= $day['sunset'] ?>
			</p>
		</div>
	</div>

	<?php elseif ($night): ?>
	<div class='nightblock  bg-midnight' >

		<h3><u>Tonight</u></h3>

		<div class=' inlineblock center' style='vertical-align:top;width:40%;'>
			<img src="<?= $night['icon'] ?>" style='width:8em;' class='auto' >
			<br />
							<?=$night['moonphase'] ?> (<?=$night['moonillum']?>%&nbsp;illum)

		</div>
		<div class=' center inlineblock' style='vertical-align:top;width:55%; font-weight:bold;width-min:350px;'>
		<p><?= $night['short'] ?></p>
				<p style='margin-top:0;font-size:1.3rem;'>
			Low: <?=$night['low']?>
			</p>
				<p>Moonrise:&nbsp;<?= $night['moonrise'] ?>  <br />
				Moonset:&nbsp;<?= $night['moonset'] ?><br /><br />

				</p>
		</div>
	</div>
	<?php endif; ?>
<div style='flex:0 0 2em;'> </div>

<!-- RIGHT PANEL -->

	<?php if ($day): ?>
	<div class='nightblock bg-midnight' >

		<h3><u>Tonight</u></h3>

		<div class=' inlineblock center' style='vertical-align:top;width:40%;'>
			<img src="<?= $night['icon'] ?>" style='width:8em;' class='auto' >
			<br />
							<?=$night['moonphase'] ?> (<?=$night['moonillum']?>%&nbsp;illum)

		</div>
		<div class=' center inlineblock' style='font-weight:bold; vertical-align:top;width:55%'>
		<p><?= $night['short'] ?></p>
				<p style='margin-top:0;font-size:1.3rem;'>
			Low: <?=$night['low']?>
			</p>
				<p>Moonrise:&nbsp;<?= $night['moonrise'] ?> <br />
				Moonset:&nbsp;<?= $night['moonset'] ?><br /><br />

				</p>
		</div>
		</div>
		<?php else: ?>

		<div class='dayblock  ' >
<h3><u>Tomorrow</u></h3>
			<div class=' inlineblock center' style='vertical-align:top; width:40%;'>
			<img src="<?= $tomorrow['icon'] ?>" style='width:8em;' class='auto' ><br />

			<b>Wind: </b> <?= $tomorrow['wind']?>
			</div >

			<div class=' center inlineblock' style='font-weight:bold;vertical-align:top;width:55%; width-min:350px;'>
			<p><?= $tomorrow['short'] ?></p>
			<p style='margin-top:0;font-size:1.3rem;'> High: <?=$tomorrow['high']?></p>
			<p>
			Sunrise:&nbsp;<?= $tomorrow['sunrise'] ?> <br/>
			Sunset:&nbsp;<?= $tomorrow['sunset'] ?>
			</p>
			</div>

		</div> <!-- end right panel -->
	<?php endif; ?>



	</div> <!-- end container -->
	<div class='inleft2 left'><small><?=$light['update']['source']?>, updated at <?=$updated?> </small></div>



