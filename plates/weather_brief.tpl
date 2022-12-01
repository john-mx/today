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
	foreach ($weather as $loc=>$days) :
		if ($loc == 'update') continue;
		$locname = Defs::$sitenames[$loc];

		?>
		<p class='sectionhead'><?=$locname?></p>

	<table class = 'in2 col-border'>
		<colgroup>

		<col style='width:33%;'>
		<col style='width:33%;'>
		<col style='width:33%;'>
		</colgroup>

		<!--
<tr>
		<?php
		// 	for ($i=1;$i<4;++$i) : //for 3 days
// 				$day = $days[$i];
// 		//	u\echor ($day ,'day',STOP);
// 				//echo "<th>{$day[0]['daytext']}</th>";
// 			endfor;
		?>
		</tr>
 -->

		<tr >
			<?php
			for ($i=1;$i<4;++$i) : //for 3 days
				echo "<td >";
				foreach ($days[$i] as $p) :
			//	u\echor($p,'period',STOP);
				?>
					<div class = '$fcclass' style='padding-top:3px;padding-bottom:3px;'>
						<b><i><?=$p['name']?></i></b>:
								<?=$p['shortForecast']?>.
								<?= $p['highlow']?>. <br />
								Wind <?=$p['windSpeed']?>;

					</div>
					<?php endforeach; #period ?>
				</td>
			<?php endfor; #day ?>
		</tr>
	</table>
	<?php endforeach; // loc?
	endif; ?>
