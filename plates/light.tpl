<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



if (!$light ){
	echo "No Data Available (light)";
	return;
}
//Utilities::echor($light,'light in tpl');

// get today's daily data
// if (! $day = $light['gday'] ?? []):
// 	echo "No data available (weather.gov day)";
// 	return;
// endif;


$day = $light['Today'] ?? [];
$night = $light['Tonight'];
$tomorrow = $light['Tomorrow']??[];
$updated = date('M d g:i a',$light['update']['ts']);
$show_day=1;
//U::echor($light,'light',STOP);
?>

<div class =' flex-container  center' >

	<!-- LEFT PANEL -->

<?php if ($day && $day['endTimets'] > time() ) :  ?>
	<div class='dayblock  highnoon'>
		<h3 class='center'>Today</h3>

		<div class=' inlineblock center' style='vertical-align:top; flex:none;width:40%;'>
			<img src="<?= $day['icon'] ?>" class='auto' ><br />

			<b>Wind: </b> <?= $day['wind']?>
		</div >


		<div class=' center inlineblock' style='font-weight:bold;vertical-align:top;flex:none;width:55%; width-min:350px;'>
			<p style='margin-top:0;margin-bottom:0;'>
			<?= $day['short'] ?><br />
			 High: <?=$day['high']?><br />
			<br />
			Sunrise:&nbsp;<?= $day['sunrise'] ?> <br />Sunset:&nbsp;<?= $day['sunset'] ?>
			</p>

		</div>
	</div>

<div style='flex:0 0 1em;'> </div>
	<div class='nightblock midnight'>

		<h3 class='center'>Tonight</h3>

		<div class=' inlineblock center' style='vertical-align:top;flex:none;width:40%;'>
			<img src="<?= $night['icon'] ?>" class='auto' >
			<br />
							<?=$night['moonphase'] ?> <br/>(<?=$night['moonillum']?>%&nbsp;illum)

		</div>
		<div class=' center inlineblock' style='vertical-align:top;flex:none;width:55%; width-min:350px;'>

		<p style='margin-top:0;margin-bottom:0'>
		<?= $night['short'] ?>
		<br />
			Low: <?=$night['low']?><br>
			<br>
				Moonrise:&nbsp;<?= $night['moonrise'] ?>  <br />
				Moonset:&nbsp;<?= $night['moonset'] ?>
			</p>

		</div>
	</div>



<?php else: ?>
<!-- RIGHT PANEL -->

	<div class='nightblock midnight'  >

		<h3 class='center'>Tonight</h3>

		<div class=' inlineblock center' style='vertical-align:top;flex:40%;'>
			<img src="<?= $night['icon'] ?>"  class='auto' >
			<br />
							<?=$night['moonphase'] ?> <br/>(<?=$night['moonillum']?>%&nbsp;illum)

		</div>
		<div class=' center inlineblock' style='font-weight:bold; vertical-align:top;width:55%'>
		<p style='margin-top:0;margin-bottom:0'>
		<?= $night['short'] ?>
		<br />
			Low: <?=$night['low']?>
		<br /><br />
				Moonrise:&nbsp;<?= $night['moonrise'] ?> <br />
				Moonset:&nbsp;<?= $night['moonset'] ?>
			</p>
		</div>
		</div>
<div style='flex:0 0 1em;'> </div>

		<div class='dayblock highnoon '>
		<h3 class='center'>Tomorrow</h3>
			<div class=' inlineblock center' style='vertical-align:top; flex:40%;' >
			<img src="<?= $tomorrow['icon'] ?>"  class='auto' ><br />

			<b>Wind: </b> <?= $tomorrow['wind']?>
			</div >

			<div class=' center inlineblock' style='font-weight:bold;vertical-align:top;width:55%; width-min:350px;'>
			<p style='margin-top:0;margin-bottom:0'>
			<?= $tomorrow['short'] ?><br />
			 High: <?=$tomorrow['high']?>
			<br /><br />
			Sunrise:&nbsp;<?= $tomorrow['sunrise'] ?> <br/>
			Sunset:&nbsp;<?= $tomorrow['sunset'] ?>
			</p>
			</div>

		</div> <!-- end right panel -->
	<?php endif; ?>



	</div> <!-- end container -->
	<div class='inleft2 left'><small><?=$light['update']['source']?>, updated at <?=$updated?> </small></div>



