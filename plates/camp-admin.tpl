<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

$open_options = U::buildOptions(['','0','a few','around 10','10 +','?'],'',true);

?>
<h4>Campground status <?php U::showHelp('campsites');?></h4>


<!--
<p><input type='checkbox' name='cgfull'
> Check to force all campgrounds full until unset.</p>
 -->
<p>
Enter update to available sites.  No entry means keep current value.
Reservation sites updated (not implemented yet) from rec.gov hourly.
</p>

<!--
<p>
Uncertainty.  <input type='number' name='uncertainty' size='4' value="<?=$camps['uncertainty'] ?? 0 ?>" min=0 max=12 > Enter number of hours the new site vacancy setting is valid.  Will be displayed to users  as '?' after the time has lapsed.
</p>
 -->

<table>
<tr><th>Campground</th><th>Status</th><th>Open Sites</th><th>Update</th><th>Notes</th></tr>
<?php foreach (array_keys(Defs::$campsites) as $scode): ?>
	<tr><td><?= Defs::$sitenames[$scode] ?></td>

		<td><select name="cgstatus[<?=$scode?>]"><?=$camps['cg_options'][$scode]?></select></td>
		<td><?=$camps['cgs'][$scode]['open']?> </td>
		<td> <!--
<input type='text' name="cgopen[<?=$scode?>]"
			 size='8' class ='cgo'>
 -->
			 <select name="cgupdate[<?=$scode?>]" class='cgo'><?=$open_options?></select></td>

		<td><input type='text' name="cgnotes[<?=$scode?>]>"
		<?php if (isset($admin['cgnotes'])) : ?>
		value='<?=$camps['cgs'][$scode]['notes']?>' <?php endif; ?>
		size=40>
		</td>
	</tr>
<?php endforeach; ?>
</table>
Click to <button type='button' onClick='clearopen()'> clear all site updates</button> (clear = no change)
<br />

