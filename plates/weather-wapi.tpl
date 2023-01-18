<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


?>

<?php
// uses wapi data

if(empty($wapi)): echo "<p>No Data</p>"; else:
	$wspec=[
	'wslocs'=> $wslocs ??= ['jr','br','cw'],
	'wsdays'=> $wsdays ??= 3,
	'wsstart'=> $wsstart ??= 0,
	];

	//set days and locations
		$locs =  $wslocs ?? ['jr','br','cw'];
		$daycnt = $wsdays ?? 3 ;

	$weather_updated =  date('M d g:i a',$wapi['update']);
	?>
	<table class = 'width100 col-border'>
	<colgroup>

	<col style='width:30%'>
	<col style='width:30%'>
	<col style='width:30%'>
	</colgroup>


<tr>

		<?php
		// note: wsstart + 0 converts to numeric
			for ($i=$wsstart+0;$i<$wsstart+$daycnt;++$i) : //for 3 days
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

		<tr class='bg-orange left'><td colspan='4'><b><?=$locname?></b></td></tr>

		<tr>

			<?php
			for ($i=$wsstart+0;$i<$wsstart+$daycnt;++$i) : //for 3 days
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
		<tr style='line-height:0.5em;'><td colspan='<?php echo $daycnt + 3 ?>'>&nbsp</td></tr>
	<?php endforeach; // loc? ?>
	</table>

	<div class='inleft2 left'><small>Forecast from weatherapi.com updated at <?=$weather_updated?>.</small></div>

<?php	endif; ?>
