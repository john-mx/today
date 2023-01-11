<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



?>

<h4>Air Quality </h4>
<?php if(0 || empty($air)): echo "<p>No Air Q Data</p>"; else:

?>
Reported by eps.gov.
<table >
<tr><th>Location</th><th>Air Quality</th><th>Reported</th></tr>
<?php
	//$air2 = ['jr' => $air['jr'] ]; // rewrite for only one loc
	foreach ($air as $loc => $dat) :

	if (! in_array($loc,array_keys(Defs::$sitenames))) continue;
	// not a valid locaiton

?>
<tr>
	<td class='left border-bottom'><?= Defs::$sitenames[$loc] ?></td>
	<td><?=$dat['aqi']?>
		<span style="background-color: <?=$dat['aqi_color']?>">
		<?=$dat['aqi_scale'] ?></span>
		</td>
	<td><?php echo date('M d h:i a',$air['jr']['observed_dt']) ?></td>

</tr>
<?php endforeach; ?>
</table>

<?php endif; ?>
