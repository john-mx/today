<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


?>

<div >

<h4>Weather Forecast</h4>


<?php

	$weather_updated =  date('M d g:i a',$weather['update']);
		//set days and locations
		$locs =  $wslocs ?? ['jr','br','cw'];
		$daycnt = $wsdays ?? 3 ;
		$wsstart = $wsstart ?? 0;
		$loccols = $daycnt + 1;

	?>
	<table class = 'col-border' style='margin:auto'>


	<tr class='no-border border-bottom'><th></th><th></th>
		<?php
			for ($i=$wsstart;$i<$wsstart+$daycnt;++$i) : //for 3 days
				$daytext = $weather['forecast']['jr'][$i]['Night']['daytext'];
		//Utilities::echor ($day ,'day',STOP);
				echo "<th>$daytext</th>";
			endfor;
		?>
		</tr>
	<?php
	foreach ($locs as $loc) :
		$days = $weather['forecast'][$loc];
		$locname = Defs::$sitenames[$loc];
		?>


		<tr style = 'border-top:1px solid black;' >
			<td rowspan='2' class='bg-orange'><b><?=$locname?></b></td>

			<td class='highnoon'><b>Day</b></td>

			<?php for ($i=$wsstart;$i<$wsstart+$daycnt;++$i) : //for 3 days ?>
				<td class='highnoon'>
				 <?php if ($p = $days[$i]['Day'] ?? ''): ?>
							<b><?=$p['short']?></b><br />
								<?= $p['temp']?>.
								<?php if ($p['wind']):echo " Wind ${p['wind']} "; endif; ?>
					<?php endif; ?>
				</td>
			<?php endfor; #day ?>
		</tr>
		<tr  class='midnight' >

			<td><b>Night</b></td>

			<?php for ($i=$wsstart;$i<$wsstart+$daycnt;++$i) : //for 3 days ?>
				<td>
				<?php if ($p = $days[$i]['Night'] ): ?>
					<div >
								<?php if ($p['short']):echo "<b>${p['short']}</b> <br> "; endif; ?>
								<?= $p['temp']?>.
								<?php if ($p['wind']):echo " Wind ${p['wind']} "; endif; ?>

					</div>
					<?php endif; ?>
				</td>
			<?php endfor; #day ?>
		</tr>
		<tr style='line-height:0.5em;'><td colspan='<?php echo $daycnt + 3 ?>'>&nbsp</td></tr>
		<?php endforeach;  ?>
	</table>

	<small><?=$weather['source']?> forecast updated at <?=$weather_updated?></small>

</div>

