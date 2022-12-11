<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<h4>Weather</h4>
<?php
//u\echor($weather,'weather',STOP);
if(empty($weather)): echo "<p>No Data</p>"; else:
	$weather_updated =  date('M d g:i a',$weather['update']);
	?>
	<table class = 'inleft2 col-border'>
	<tr class='border-bottom'><td colspan='5' class='left'>
	<small>Weather.gov forcast updated at <?=$weather_updated?></small>
	</td></tr>

<tr><th></th>
		<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				$day = $weather['jr'][$i];
		//u\echor ($day ,'day',STOP);
				echo "<th>{$day[0]['daytext']}</th>";
			endfor;
		?>
		</tr>

<?php	foreach ($weather as $loc=>$days) :
		if ($loc == 'update') continue;
		$locname = Defs::$sitenames[$loc];

		?>

		<tr class='bg-orange'><td colspan=4'><b><?=$locname?></b></td></tr>

		<tr>
		<td>Day</td>
			<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				$p = $days[$i][0] ;

				?>
					<td>
							<?php if (count($days[$i]) == 2) : ?>
								<?=$p['shortForecast']?> <br />
								<?= $p['highlow']?>.
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
								<?= $p['highlow']?>.
								Wind <?=$p['windSpeed']?>;

					</div>
					</td>
			<?php endfor; #day ?>
 </tr>
	<?php endforeach; // loc? ?>
	</table>
<?php	endif; ?>
