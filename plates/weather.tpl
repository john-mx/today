<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<h4>Weather</h4>
<?php
// weather supplies both formatted wgov and formatted wapi.

//u\echor($wgov,'weather',STOP);
if(! isset($wgov['jr'])): echo "<p>No Data</p>"; else:
	$weather_updated =  date('M d g:i a',$wgov['update']);
	?>
	<table class = 'width100 col-border '>
	<colgroup>
	<col style='width:10%'>
	<col style='width:30%'>
	<col style='width:30%'>
	<col style='width:30%'>
	</colgroup>

	<tr class='border-bottom'><td colspan='5' class='left'>
	<small>Weather.gov forcast updated at <?=$weather_updated?></small>
	</td></tr>

<tr><th></th>
		<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				$day = $wgov['jr'][$i];
		//u\echor ($day ,'day',STOP);
				echo "<th>{$day[0]['daytext']}</th>";
			endfor;
		?>
		</tr>

<?php	foreach ($wgov as $loc=>$days) :
		if ($loc == 'update') continue;
		//if ($loc !== 'jr') continue; // only show jr
		$locname = Defs::$sitenames[$loc];

		?>

		<tr class='bg-orange left'><td colspan='4'><b><?=$locname?></b></td></tr>

		<tr>
		<td>Day</td>
			<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				$p = $days[$i][0] ;

				?>
					<td>
							<?php if (count($days[$i]) == 2) : ?>
								<?=$p['shortForecast']?> <br />
								<?= $p['highlow']?>.<br />
								Wind <?=$p['windSpeed']?>;
							<?php endif; ?>

					</td>
			<?php endfor; #day ?>
		</tr>
		<tr >

			<td class='bg-midnight white'>Night</td>
			<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				if (count($days[$i]) == 2) :
					$p = $days[$i][1] ;
				else :
					$p = $days[$i][0] ;
				endif;
				?>
					<td class='bg-midnight white'>
					<div >
								<?=$p['shortForecast']?>  <br />
								<?= $p['highlow']?>.<br />
								Wind <?=$p['windSpeed']?>;

					</div>
					</td>
			<?php endfor; #day ?>
 </tr>
	<?php endforeach; // loc? ?>
	</table>
<?php	endif; ?>
