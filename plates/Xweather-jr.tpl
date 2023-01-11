<?php



?>
<h4>3-Day Forecast</h4>
<?php
// uses wgov data
//Utilities::echor($weather,'weather',NOSTOP);
if(empty($weather)): echo "<p>No Data</p>"; else:
	$weather_updated =  date('M d g:i a',$weather['update']);
	?>
	<table class = 'width100 col-border'>
	<colgroup>
	<col style='width:10%'>
	<col style='width:30%'>
	<col style='width:30%'>
	<col style='width:30%'>
	</colgroup>



<tr><th></th>
		<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				$day = $weather['jr'][$i];
		//Utilities::echor ($day ,'day',STOP);
				echo "<th>{$day[0]['daytext']}</th>";
			endfor;
		?>
		</tr>

<?php	foreach ($weather as $loc=>$days) :
		if ($loc == 'update') continue;
		if (! in_array($loc,[ 'jr','cw'])) continue; // only show jr
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
		<tr class='bg-blue '>

			<td >Night</td>
			<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				if (count($days[$i]) == 2) :
					$p = $days[$i][1] ;
				else :
					$p = $days[$i][0] ;
				endif;
				?>
					<td>
					<div >
								<?=$p['shortForecast']?>  <br />
								<?= $p['highlow']?>.<br />
								Wind <?=$p['windSpeed']?>;

					</div>
					</td>
			<?php endfor; #day ?>
 </tr>
 <tr ><td colspan='4' class='no-col' style='line-height:0.6rem;'>&nbsp;</td></tr>
	<?php endforeach; // loc? ?>
	</table>
	<div class='inleft2 left'>
	<small>Forecast from weather.gov updated at <?=$weather_updated?>. (w-jr)</small>
	</div>
<?php	endif; ?>
