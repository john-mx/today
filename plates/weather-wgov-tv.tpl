<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


?>

<div >

<h4>Weather Forecast</h4>


<?php

	$weather_updated =  date('M d g:i a',$wgov['update']);
		//set days and locations
		$locs =  $wslocs ?? ['jr','br','cw'];
		$daycnt = $wsdays ?? 3 ;
		$fcstart = $fcstart ?? 0;

	?>
	<table class = 'col-border' style='margin:auto'>


	<tr class='no-border border-bottom'><th></th><th></th>
		<?php
			for ($i=$fcstart+1;$i<$fcstart+$daycnt+1;++$i) : //for 3 days
				$daytext = $wgov['jr'][$i]['Night']['daytext'];
		//Utilities::echor ($day ,'day',STOP);
				echo "<th>$daytext</th>";
			endfor;
		?>
		</tr>
	<?php
	foreach ($locs as $loc) :
		$days = $wgov[$loc];
		$locname = Defs::$sitenames[$loc];
		?>


		<tr style = 'border-top:1px solid black;' >
			<td rowspan='2' class='bg-orange'><b><?=$locname?></b></td>

			<td>Day</td>

			<?php for ($i=$fcstart+1;$i<$fcstart+$daycnt+1;++$i) : //for 3 days ?>
				<td>
				 <?php if ($p = $days[$i]['Day'] ?? ''): ?>
							<?=$p['shortForecast']?><br />
								<?= $p['highlow']?>.
								Wind <?=$p['windSpeed']?>;
					<?php endif; ?>
				</td>
			<?php endfor; #day ?>
		</tr>
		<tr  class='bg-midnight white' >

			<td>Night</td>

			<?php for ($i=$fcstart+1;$i<$fcstart+$daycnt+1;++$i) : //for 3 days ?>
				<td>
				<?php if ($p = $days[$i]['Night'] ): ?>
					<div >
								<?=$p['shortForecast']?><br />
								<?= $p['highlow']?>.
								Wind <?=$p['windSpeed']?>;

					</div>
					<?php endif; ?>
				</td>
			<?php endfor; #day ?>
		</tr>
		<tr style='line-height:0.5em;'><td colspan='<?php echo $daycnt + 3 ?>'>&nbsp</td></tr>
		<?php endforeach;  ?>
	</table>

	<small>Weather.gov forcast updated at <?=$weather_updated?></small>

</div>

