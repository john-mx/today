<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



?>

<h4>Air Quality </h4>
<?php if(0 || empty($air)): echo "<p>No Air Q Data</p>"; else:
// echo "Retrieved at  " . date ('M j h:i a',$air['jr']['dt']);
?>
As of <?php echo date('M d h:i a',$air['jr']['dt']) ?>
<table >
<tr><th>Location</th><th>Air Quality</th><th>Particulates (PM10)</th><th>Ozone</th></tr>
<?php foreach ($air as $loc => $dat) :
	if (! LS::getLocName($loc)) continue;
	// not a valid locaiton

	$rdt = date ('F j H:ia',$dat['dt']);
?>
<tr>
	<td class='left border-bottom'><?= LS::getLocName($loc) ?></td>
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
