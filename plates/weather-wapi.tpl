<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<h4>Weather</h4>
<?php
// uses wapi data

if(empty($wapi)): echo "<p>No Data</p>"; else:


	$weather_updated =  date('M d g:i a',$wapi['update']);
	?>
	<table class = 'width100 col-border'>
	<colgroup>

	<col style='width:30%'>
	<col style='width:30%'>
	<col style='width:30%'>
	</colgroup>

	<tr class='border-bottom'><td colspan='4' class='left'>
	<small>Weatherapi.com forcast updated at <?=$weather_updated?></small>
	</td></tr>

<tr>
		<?php
			for ($i=0;$i<3;++$i) : //for 3 days
				$day = $wapi['forecast']['jr'][$i];
		//u\echor ($day ,'day',STOP);
				echo "<th>{$day['date']}</th>";
			endfor;
		?>
		</tr>

<?php	foreach ($wapi['forecast'] as $loc=>$days) :
		if ($loc == 'update') continue;
		if (! in_array($loc,[ 'jr','cw'])) continue; // only show jr
		$locname = Defs::$sitenames[$loc];

		?>

		<tr class='bg-orange left'><td colspan='4'><b><?=$locname?></b></td></tr>

		<tr>

			<?php
			for ($i=0;$i<3;++$i) : //for 3 days
				$p = $days[$i] ;

				?>
					<td>
						<?=$p['skies']?><br />
						High: <?=$p['High']?> &deg;F (<?=$p['HighC']?> &deg;C)<br />
						Low: <?=$p['Low']?> &deg;F (<?=$p['LowC']?> &deg;C)<br />
						Wind: <?=$p['maxwind']?> mph (<?=$p['maxwindM']?> kph)


					</td>
			<?php endfor; #day ?>
		</tr>

	<?php endforeach; // loc? ?>
	</table>
<?php	endif; ?>
