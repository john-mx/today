<?php
use DigitalMx as u;


if (!$light || ! $wapi ){
	echo "No Data Available (light)";
	return;
}
//u\echor($light,'light in tpl');

$astro = $light['astro'] ?? [];
$uv = $light['uv'] ?? [];
$air = $air;
// get today's daily data
if (! $gday = $light['gday'] ?? []):
	echo "No data available (weather.gov day)";
	return;
endif;



$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0] ?? [];
//u\echor($wday,'wday');

//u\echor($gday,'gday',NOSTOP);

?>
<?php if (1 &&
	isset ($gday) && (time() - $wgov['update'] < 8*60*60)
	): #use wgov
?>
<div class ='center clearafter flex-container' style='vertical-align:top' >

	<div class='border center floatl' style=' font-weight:bold; width:48%; vertical-align:top; ' >
		<h3><u>Today</u></h3>
		<?php
			if (!isset($gday)): echo "Data not available";
			elseif (!isset($gday['day'])): echo "Day has ended";
			else :
		?>

		<div class=' inlineblock center' style='vertical-align:top; width:40%;'>
			<img src="<?= $gday['day']['icon'] ?>" class='auto' ><br />
			<?= $gday['day']['shortForecast'] ?><br />
			<b>Wind: </b> up to <?= $wday['maxwind']?>&nbsp;mph (<?= $wday['maxwindM'] ?>&nbsp;kph)
		</div >

		<div class=' center inlineblock' style='vertical-align:top;width:55%'>
			<p style='margin-top:0;font-size:1.2rem;'><?=$gday['day']['highlow']?></p>
			<p>
			Sunrise:&nbsp;<?= $astro['sunrise'] ?> Sunset:&nbsp;<?= $astro['sunset'] ?>
			</p>
		</div>
		<?php endif; ?> <!-- end content -->
	</div> <!-- end today -->


	<div class='border center floatr  bg-blue ' style=' font-weight:bold; width:45%; vertical-align:top;' >
		<h3><u>Tonight</u></h3>

		<div class=' inlineblock center' style='vertical-align:top;width:40%;'>
			<img src="/images/moon/<?= $astro['moonpic'] ?>" style='width:76px' class='auto' >
			<br />
							<?=$astro['moonphase'] ?> (<?=$astro['moonillumination']?>%&nbsp;illum)

		</div>
		<div class=' center inlineblock' style='vertical-align:top;width:55%'>
				<p style='margin-top:0;font-size:1.2rem;'>Low:
			<?=$wday['Low']?> &deg;F
			(<?=$wday['LowC']?> &deg;C)
			</p>
				<p>Moonrise:&nbsp;<?= $astro['moonrise'] ?>  Moonset:&nbsp;<?= $astro['moonset'] ?><br /><br />

				</p>
		</div>
	</div> <!-- end tonight -->


	</div> <!-- end container -->
	<div class='inleft2 left'><small>Weather data at Jumbo Rocks from weather.gov</small></div>
		<?php
	///////////////////////
		else: #use wapi

	////////////////////////
	?>
<div class ='center clearafter flex-container' style='vertical-align:top' >

<div class='border center floatl' style=' font-weight:bold; width:48%; vertical-align:top; ' >
<h3><u>Today</u></h3>

		<div class=' inlineblock center' style='vertical-align:top; width:40%;'>
			<img src="https:<?= $wday['icon'] ?>" class='auto' ><br />
			<?= $wday['skies'] ?><br />
			<b>Wind: </b> up to <?= $wday['maxwind']?>&nbsp;mph (<?= $wday['maxwindM'] ?>&nbsp;kph)
		</div >

		<div class=' center inlineblock' style='vertical-align:top;width:55%'>
			<p style='margin-top:0;font-size:1.2rem;'>High:
			<?=$wday['High']?> &deg;F
			(<?=$wday['HighC']?> &deg;C)
			</p>
			<p>
			Sunrise:&nbsp;<?= $astro['sunrise'] ?> Sunset:&nbsp;<?= $astro['sunset'] ?>
			</p>
		</div>
	</div> <!-- end today -->


<div class='border center floatr bg-blue' style=' font-weight:bold; width:45%; vertical-align:top;' >

		<h3><u>Tonight</u></h3>

			<div class= 'inlineblock center ' style='vertical-align:top;width:40%;'>
<img src="/images/moon/<?= $astro['moonpic'] ?>" style='width:76px' class='auto' >
				<br />
					<?=$astro['moonphase'] ?> (<?=$astro['moonillumination']?>%&nbsp;illum)
			</div>

			<div class=' center inlineblock' style='vertical-align:top;width:55%'>
								<p style='margin-top:0;font-size:1.2rem;'>Low:
							<?=$wday['Low']?> &deg;F
							(<?=$wday['LowC']?> &deg;C)
							</p>
								<p>Moonrise:&nbsp;<?= $astro['moonrise'] ?>  Moonset:&nbsp;<?= $astro['moonset'] ?><br /><br />

					</p>
			</div>
	</div><!-- end tonight -->


	</div><!-- end wapi container-->
	<div class= 'left inleft2' >	<small>Weather at 29 Palms from weatherapi.com</small>
	</div>
<?php endif; ?>

