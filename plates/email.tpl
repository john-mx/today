<?php
// this plate uses inline css for emails
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<?php
	$site_path = SITE_PATH;
	$site_url = SITE_URL;
?>
<html>
<style>
	table tr td {border:0;border-collapse:collapse;}
	h4 {text-align:left;font-size:1.2em;font-weight:bold;margin-bottom:0.2em;margin-top:0.8em;}

</style>

<table style='max-width:600px;border:1px solid black'>
<tr><td style='border:0 text-align:center;'>
<p><b><u>Today in Joshua Tree National Park</u></b></p>
<?=$target ?>
<br />
<?php if(!empty($admin['pithy'])): ?>
<p class='pithy'><i><?=$admin['pithy'] ?></i></p>
<?php endif; ?>

</td></tr>

<?php if (!empty($admin['alerts'])) : ?>
	<tr><td style='text-align:left;'>
	<h4>Active Alerts </h4>
	<ul>
	<?php $anlist = explode("\n",$admin['alerts']);
		foreach ($anlist as $item) :
			if (!empty(trim($item))):
				echo "<li style='color:red;'>$item</li>";
			endif;
		endforeach ?>
		</ul>
		<br />
	</td></tr>
<?php endif; ?>



<?php if(!empty($admin['announcements'])) : ?>
<tr><td style='text-align:left;'>
	<h4>Announcements</h4>
	<ul>
	<?php $anlist = explode("\n",$admin['announcements']);
		foreach ($anlist as $item) :
			if (!empty(trim($item))):
				echo "<li>$item</li>";
			endif;
		 endforeach ?>
	</ul>
	<br />
	</td>
	</tr>
<?php endif; ?>

<tr><td style='text-align:left;'>
<h4>Light and Dark</h4/>
	Sunrise <?=$light['sunrise']?> Set <?=$light['sunset']?><br />
Moonrise <?=$light['moonrise']?> Set <?=$light['moonset']?> <?=$light['moonphase']?>
</td></tr>

<tr><td style='text-align:left;'>
	<h4>UV Exposure:</h4>
	<p style = 'background-color:<?=$uv['uvcolor']?>;'> <b><?= $uv['uv'] ?></b>  <?=$uv['uvscale']?></p>
	<b>For UV = <?=$uv['uvscale']?></b><br><?=$uv['uvwarn']?>
<br />
</td></tr>

<tr><td style='text-align:left;'>
<h4>Fire Danger: </h4>
	 	<p  Current Level: <span style="background-color:<?=$fire['color']?>">
	 	<b><?=$fire['level']?></b> </span></p>

	<?=Defs::$firewarn[$fire['level']]?>
</td></tr>

<tr><td style='text-align:left;'>
<h4>Air Quality</h4>
<table style='margin-left:2em; border:0'>
<tr><th>Location</th><th>Air Quality</th><th>Particulates (PM10)</th><th>Ozone</th></tr>
<?php foreach ($air as $loc => $dat) :
	if (! in_array($loc,array_keys(Defs::$sitenames))) continue;
	// not a valid locaiton

	$rdt = date ('M j H:ia',$dat['dt']);
?>
<tr style="border-bottom:1px solid gray;">
	<td style='text-align:left;'><?= Defs::$sitenames[$loc] ?></td>
	<td><?=$dat['aqi']?>
		<span style="background-color: <?=$dat['aqi_color']?>">
		<?=$dat['aqi_scale'] ?></span>
		</td>
	<td><?=$dat['pm10']?></td>
	<td><?=$dat['o3']?></td>

</tr>
<?php endforeach; ?>
</table>
<br />
</td></tr>

<tr><td style='text-align:left;'>

<h4>Weather</h4>
<?php
	$weather = $wapi['fc'];
	$periods = [0,1,2];
?>
	<table style='margin-left:2em;'>
			<tr><th></th>
<?php
		foreach ($periods as $p) :
			echo "<th>{$weather['forecast']['jr'][$p]['date']}</th>";
		endforeach;
?>
		</tr>
<?php
	foreach ($weather['forecast'] as $loc => $x ) : //x period array
			if (! $locname = Defs::$sitenames[$loc] ) : continue; endif;
	?>
			<tr style='border-bottom:1px solid gray;'>
			<td  style='border-bottom:1px solid gray;' ><b><?=$locname?></b></td>
	<?php
				foreach ($periods as $p) :
					echo  "<td style='border-bottom:1px solid gray;'><p>";

						$v = $x[$p]['skies'] ;
						echo "$v<br />";

						$v = $x[$p]['Low'] ;
						$w = $x[$p]['High'] ;
						echo "Low: $v High: $w  &deg;F<br />";

						$v = $x[$p]['maxwind'] ;
						echo "Wind to $v mph <br />";

						$v = $x[$p]['avghumidity'] ;
						echo "Humidity: $v %<br />";

						$v = $x[$p]['rain'] ;
						echo "Rain $v %<br />";

					echo 	"</p></td>\n" ;
				endforeach;
	?>
	</tr>
	<?php endforeach ?>
	</table>
	<br />
</td></tr>


<tr><td style='text-align:left;'>
<h4>Campgrounds</h4>

<?php if (!empty($campgroundadivse)) : ?>
	<div class='warn'><?=$campgroundadvise?></div>
<?php endif; ?>

<?php if(empty($camps)): echo "No Data"; else: ?>
<table style='margin-left:2em;border:2px solid black;'>
<tr><th></th><th>Availability</th><th>Status</th></tr>
<?php foreach (['ic','jr','sp','hv','be','wt','ry','br','cw'] as $cg) : ?>

	<tr>
		<td style='text-align:left; border-bottom:1px solid gray;'>  <?=Defs::$sitenames [$cg] ?>  </td>
	 <td  style='text-align:center;border-bottom:1px solid gray;'> <?= $camps['cgavail'][$cg] ?> </td>
	<td style='text-align:center;border-bottom:1px solid gray;'> <?= $camps['cgstatus'][$cg] ?>  </td>
	</tr>
	<?php endforeach;?>

</table>
<?php endif; ?>
<br />
</td></tr>

<tr><td style='text-align:left;'>

<h4>Events</h4>
<?php if(empty($calendar)) : echo "No Data"; else:
?>

<table style='border:0'>
<!-- <tr><th>Date and Time</th><th>Location</th><th>Type</th><th>Title</th></tr> -->
<tbody>
<?php $calempty = 1;
	foreach ($calendar as $cal) :
	// stop looking if more than 3 days out
if (($cal['dt'] < time() ) || ($cal['dt'] > (time() + 3600*24*3 ))) continue;
	$calempty = 0;
	$datetime = date('l M j g:i a', $cal['dt']);
	?>
	<tr style='border-bottom:1px solid gray;' >
	<td style='vertical-align:top;border-bottom:1px solid gray;'><?=$datetime ?> <br />&nbsp;&nbsp;(<?=$cal['duration']?>) </td>

 	<td style='text-align:left; border-bottom:1px solid gray;'>
 	<b><?=$cal['title']?></b><br />
 	<?=$cal['type'] ?>  at <?=$cal['location']?>  <br />

	<?php if (!empty($cal['note'])) : ?>
		<p><?=$cal['note'] ?></p>
	<?php endif; ?>
	</td>
 </tr>

<?php endforeach; ?>
<?php if($calempty): echo "No Events in next 3 days"; endif; ?>
</tbody>

</table>

<?php endif; ?>

</td></tr>
</table>

<hr>
<p id='bottom' class='right'><?=$version ?>
<br>build <?php echo date('dHi'); ?></p>
