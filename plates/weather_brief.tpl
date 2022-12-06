<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<div >

<h4>Weather Forecast</h4>
<?php if (!empty($admin['weather_warn'])) : ?>
	<p class='in2 inline'><b>Local Warning</b></p> Updated <?=$admin['updated']?>
	<div class='warn'><?=$admin['weather_warn']?></div>
<?php endif; ?>


<?php
if(empty($weather)): echo "<p>No Data</p>"; else:
	echo "Weather.gov forcast updated at " . date('M d g:i a',$weather['update']) . BR;
	?>
	<table class = 'in2 col-border'>

	<tr><th></th><th></th>
		<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				$day = $weather['jr'][$i];
		//u\echor ($day ,'day',STOP);
				echo "<th>{$day[0]['daytext']}</th>";
			endfor;
		?>
		</tr>
	<?php
	foreach ($weather as $loc=>$days) :
		if ($loc == 'update') continue;
		$locname = Defs::$sitenames[$loc];
		?>


		<tr style = 'border-top:1px solid black;'>
			<td rowspan='2'><b><?=$locname?></b></td>

			<td>Day</td>
			<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				$p = $days[$i][0] ;

				?>
					<td>
							<?php if (count($days[$i]) == 2) : ?>
								<?=$p['shortForecast']?>.
								<?= $p['highlow']?>. <br />
								Wind <?=$p['windSpeed']?>;
							<?php endif; ?>

					</td>
			<?php endfor; #day ?>
		</tr>
		<tr >

			<td class='bg-black white'>Night</td>
			<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				if (count($days[$i]) == 2) :
					$p = $days[$i][1] ;
				else :
					$p = $days[$i][0] ;
				endif;
				?>
					<td class='bg-black white'>
					<div >
								<?=$p['shortForecast']?>.
								<?= $p['highlow']?>. <br />
								Wind <?=$p['windSpeed']?>;

					</div>
					</td>
			<?php endfor; #day ?>
		</tr>
		<?php endforeach; // loc? ?>
	</table>
</div>
	<?php endif; ?>
