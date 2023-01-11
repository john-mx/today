<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


?>

<?php
// uses wapi data
// this format used on summary report to get a one-line
// weather report.

if( 0 && !empty($wapi)):
	$wspec=[
	'wslocs'=> $wslocs ??= ['jr','br','cw'],
	'wsdays'=> $wsdays ??= 3,
	'fcstart'=> $fcstart ??= 0,
	];

	//set days and locations
		$locs =  $wslocs ?? ['jr','br','cw'];
		$daycnt = $wsdays ?? 3 ;

	$weather_updated =  date('M d g:i a',$wapi['update']);
	?>
	<table class = ' col-border indent'>



<tr>
<td></td>
		<?php
		// note: fcstart + 0 converts to numeric
			for ($i=$fcstart;$i<$fcstart+$daycnt;++$i) : //for 3 days
				$day = $wapi['forecast']['jr'][$i];
// 		Utilities::echor ($day ,'day',STOP);
				echo "<th>{$day['date']}</th>";
			endfor;
		?>
		</tr>

<?php	foreach ($locs as $loc) :
		$days = $wapi['forecast'][$loc];

		$locname = Defs::$sitenames[$loc];

		?>


		<td><b><?=$locname?></b></td>
			<?php
			for ($i=$fcstart;$i<$fcstart+$daycnt;++$i) : //for 3 days
				$p = $days[$i] ;
				?>
					<td class='lrpad'>
						<?=$p['short']?><br />
						High:&nbsp;<?=$p['High']?>&deg;F&nbsp;(<?=$p['HighC']?>&deg;C)
						Low:&nbsp;<?=$p['Low']?>&deg;F&nbsp;(<?=$p['LowC']?>&deg;C)<br />
						Wind: <?=$p['maxwind']?> mph (<?=$p['maxwindM']?> kph)


					</td>
			<?php endfor; #day ?>
		</tr>

	<?php endforeach; // loc? ?>
	</table>

	<div class='inleft2 left'><small>Forecast from weatherapi.com updated at <?=$weather_updated?>. (wapi)</small></div>

<?php	endif; ?>

<?php
	if(!empty($wgov)):
//	Utilities::echor($wgov,'wgov to 1line');
	$wspec=[
	'wslocs'=> $wslocs ??= ['jr','br','cw'],
	'wsdays'=> $wsdays ??= 3,
	'fcstart'=> $fcstart ??= 0,
	];

	//set days and locations
		$locs =  $wslocs ?? ['jr','br','cw'];
		$daycnt = $wsdays ?? 3 ;

	$weather_updated =  date('M d g:i a',$wgov['update']);
	?>
	<table class = ' col-border indent'>



<tr>
<td></td>
		<?php
		// note: fcstart + 0 converts to numeric
			for ($i=$fcstart+1;$i<$fcstart+$daycnt+1;++$i) : //for 3 days
				$day = $wgov['jr'][$i];
// 		Utilities::echor ($day ,'day',STOP);
				echo "<th>{$day['Day']['name']}</th>";
			endfor;
		?>
		</tr>

<?php	foreach ($locs as $loc) :
		$days = $wgov[$loc];

		$locname = Defs::$sitenames[$loc];

		?>


		<td><b><?=$locname?></b></td>
			<?php
			// today is i=1, so fcstart +1
			for ($i=$fcstart+1;$i<$fcstart+$daycnt+1;++$i) : //for 3 days
				$p = $days[$i] ;
//Utilities::echor($p,$loc. ' $p ' .$i);


				?>
					<td class='lrpad'>
						<b><?=$p['Day']['shortForecast']?></b><br />

					<b><?= $p['Day']['highlow'] ?>
					<?= $p['Night']['highlow'] ?></b>
					<br />
					Night: <?=$p['Night']['shortForecast']?><br />
						Wind: <?=$p['Day']['windSpeed']?> mph


					</td>
			<?php endfor; #day ?>
		</tr>

	<?php endforeach; // loc? ?>
	</table>

	<div class='inleft2 left'><small>Forecast from weatherapi.com updated at <?=$weather_updated?>. (wapi)</small></div>

<?php	endif; ?>
