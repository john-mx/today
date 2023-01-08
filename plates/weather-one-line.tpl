<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php
// uses wapi data
// this format used on summary report to get a one-line
// weather report.

if(empty($wapi)): echo "<p>No Data</p>"; else:
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
	<table class = 'width100 col-border inleft2'>



<tr>
<td></td>
		<?php
		// note: fcstart + 0 converts to numeric
			for ($i=$fcstart+0;$i<$fcstart+$daycnt;++$i) : //for 3 days
				$day = $wapi['forecast']['jr'][$i];
// 		u\echor ($day ,'day',STOP);
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
			for ($i=$fcstart+0;$i<$fcstart+$daycnt;++$i) : //for 3 days
				$p = $days[$i] ;
				?>
					<td>
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
