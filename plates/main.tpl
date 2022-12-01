<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<div >
<h2 class='tdate'><?=$target ?> </h2>
<p class='pithy'><?=$admin['pithy'] ?? '' ?></p>
</div>

<!-- ############################## -->
<div id='page1'>
<h3>Announcements </h3>
<?php if(!empty($admin['announcements'])) : ?>
	<div class='in2 border'><ul>
	<?php $anlist = explode("\n",$admin['announcements']);
		foreach ($anlist as $item) :?>
			<li><?=$item?></li>
		<?php endforeach ?>
		</ul>
	</div><br />
<?php endif; ?>

<?php if (!empty($admin['alerts'])) : ?>
<h3>Active Alerts </h3>
	<div class='in2 warn'>
	<ul>
	<?php $anlist = explode("\n",$admin['alerts']);
		foreach ($anlist as $item) :
			if (empty(trim($item))):continue;endif;
			echo "<li>$item</li>";
		endforeach ?>
		</ul>
	</div><br />
<?php endif; ?>

<h3>Light and Dark</h3>
<?php if(empty($light)): echo "<p>No Data</p>"; else: ?>
<table class = 'in2'>
<colgroup>
	<col style='width:50%;'>
	<col style='width:50%;'>

</colgroup>

<!-- <tr class='no-border'><td ><b>Today</b></td><td class='bg-black white'><b>Tonight</b></td></tr> -->
<tr class='no-border'>
	<td><b>Sunrise</b> <?=$light['sunrise']?> Set <?=$light['sunset']?> </td>
<td class='bg-black white' ><b>Moonrise</b> <?=$light['moonrise']?> Set <?=$light['moonset']?></td>
</tr>

<tr class='no-border'>
	<td ><p style='width:100%'><b>UV Exposure:</b> <?= $uv['uv'] ?>
	<span style = 'background-color:<?=$uv['uvcolor']?>;'>   <?=$uv['uvscale']?></span></p>
	<p><?=$uv['uvwarn']?></p>

	</td>
	<td class='bg-black' ><p class='white'><?=$light['moonphase']?></p>
	<img src= "/images/moon/<?=$light['moonpic'] ?>"  alt="<?=$light['moonphase']?>" /></td>
</tr>
</table>
<br />
<?php endif; ?>
<!-- ####################################### -->
</div><br /><div id='page2'>



<?php
  $this->insert('calendar',['calendar' =>$calendar])?>


<!-- ####################################### -->
</div><br /><div id='page3'>




<?php if(0 || empty($air)): echo "<p>No Data</p>"; else:
// echo "Retrieved at  " . date ('M j h:i a',$air['jr']['dt']);
?>
<h3>Air Quality </h3>
<table class='in2' >
<tr><th>Location</th><th>Air Quality</th><th>Particulates (PM10)</th><th>Ozone</th></tr>
<?php foreach ($air as $loc => $dat) :
	if (! in_array($loc,array_keys(Defs::$sitenames))) continue;
	// not a valid locaiton

	$rdt = date ('M j H:ia',$dat['dt']);
?>
<tr>
	<td class='left border-bottom'><?= Defs::$sitenames[$loc] ?></td>
	<td><?=$dat['aqi']?>
		<span style="background-color: <?=$dat['aqi_color']?>">
		<?=$dat['aqi_scale'] ?></span>
		</td>
	<td><?=$dat['pm10']?></td>
	<td><?=$dat['o3']?></td>

</tr>
<?php endforeach; ?>
</table>

<?php endif; ?>
<div style='clear:left;'></div>
<!-- ####################################### -->
</div><br /><div id='page24'>
<h3>Weather</h3>
<?php $weather = $wapi['fc'];
if(empty($weather)): echo "<p>No Data</p>"; else: ?>

	<table class = 'in2 '>

	<!-- get period names -->
	<?php
		$periods = [0,1,2];

		echo "<tr><th>Forecast</th>";
		foreach ($periods as $p) :
			echo "<th>{$weather['forecast']['jr'][$p]['date']}</th>";
		endforeach;
		echo "</tr>";

// change this to deisgnate which locations tyo report

	foreach ($weather['forecast'] as $loc => $x ) : //x period array
			if ($loc == 'alerts') : continue; endif;
			// shows up in weather file like a location.
			// is captured separately for the alerts cache

			if (! $locname = Defs::$sitenames[$loc] ) : continue; endif;
	//	u\echor ($x,"Loc $loc", STOP);
	?>
			<tr class='borders '><td ><b><?=$locname?></b></td>




	<?php
				foreach ($periods as $p) :
					echo  "<td>";

						$v = $x[$p]['skies'] ;
						echo "$v<br />";

						$v = $x[$p]['Low'] ;
						$w = $x[$p]['High'] ;
						echo "$v &ndash; $w  &deg;F. ";

						$v = $x[$p]['maxwind'] ;
						echo "Wind to $v mph <br />";

						$v = $x[$p]['avghumidity'] ;
						//echo "Humidity: $v % ";

						$v = $x[$p]['rain'] ;
						echo "Rain $v %<br />";

					echo 	"</td>\n" ;
				endforeach;
	?>
		</tr>
	<?php endforeach ?>
	</table>
<br />
<?php endif; ?>


<!-- ####################################### -->
</div><br /><div id='page25'>

<?php $this->insert('campground',['camps'=>$camps]); ?>



</div>
<hr>
<p id='bottom' class='right'><?=$version ?>
<br>build <?php echo date('dHi'); ?></p>
