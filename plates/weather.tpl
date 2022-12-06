<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<h4>Weather Forecast
<span style="font-weight:normal;font-size:1rem;;">
<?php
if(empty($weather)): echo "No Data"; else:
	echo "(Updated from Weather.gov at " . date('M d g:i a',$weather['update']).")" . NL;
	?>
	</span></h4>

	<table class = 'in2 col-border'>
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
<?php	endif; ?>
