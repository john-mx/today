<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<h4>Air Quality </h4>
<?php if(0 || empty($air)): echo "<p>No Air Q Data</p>"; else:
// echo "Retrieved at  " . date ('M j h:i a',$air['jr']['dt']);
?>
As of <?php echo date('M d h:i a',$air['jr']['dt']) ?>
<table >
<tr><th>Location</th><th>Air Quality</th><th>Particulates (PM10)</th><th>Ozone</th></tr>
<?php foreach ($air as $loc => $dat) :
	if (! in_array($loc,array_keys(Defs::$sitenames))) continue;
	// not a valid locaiton

	$rdt = date ('Fß j H:ia',$dat['dt']);
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
