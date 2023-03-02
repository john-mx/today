<?php
use DigitalMx\jotr\LocationSettings as LS;
use DigitalMx\jotr\Utilities as U;

?>
<p>
Enter update to available sites.  No entry means keep current value.
Reservation sites updated from recreation.gov hourly. Stale data shown in with red background.
</p> <?php U::showHelp('campsites');?>

<table>
<tr><th>Campground</th><th>Status</th><th>Open Sites</th><th>As of</th><th>Update</th><th>Notes</th></tr>

<?php $keys = array_keys($camps['cgs']);
	sort ($keys);
	foreach ($keys as $scode):
		$style = ($camps[$scode]['stale'] > 0)? '#FCC':'#FFF';
	?>
	<tr><td><?= LS::getLocName($scode) ?></td>

		<td><select name="campu[<?=$scode?>][status]"><?=$camps['cgs'][$scode]['statusopt']?></select></td>

		<td style='background-color:<?=$style?>'><?=$camps['cgs'][$scode]['open'] ?></td>
		<td><?=$camps['cgs'][$scode]['asofHM']?></td>
		<td>
<input type=text name="campu[<?=$scode?>][cgupdate]" class='cgo' size='8' value = ''></td>
		<td><input type='text' name="campu[<?=$scode?>][notes]>"
		<?php if (isset($camps['cgs'][$scode]['notes'])) : ?>
		value="<?=$camps['cgs'][$scode]['notes'] ?>" <?php endif; ?>
		size=40>
		</td>
	</tr>
<?php endforeach; ?>
</table>
Click to <button type='button' onClick='clearopen()'> clear all site updates</button> (clear = no change)
<br /><br/>
<button class='submit' type='submit'>Submit Form</button>
