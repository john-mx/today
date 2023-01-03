<?php

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
$open_options = u\buildOptions(['','0','a few','around 10','10 +','?'],'',true);


?>

<form method='post'>
<input type='hidden' name='type' value='update'>
<h4>Say something pithy</h4>
	<input name='pithy' type='text' size='80' value='<?=$this->e($admin['pithy'])?>'>
</p>



<h4>Enter alerts</h4>
<p>Enter any alert you wish to display.  Click <button type='button' onClick = "showDiv('galerts');"> Active Alerts</button> to view active alerts from weather.gov. Copy and edit as appropriate. </p>

<div id='galerts' class='hidden'>
	<?php foreach ($galerts as $source=>$alertset) :
		//u\echor($alertset,$source);
		$sourcename =Defs::$sources[$source];
	?>
	<hr style="height:4px;background-color:green;">
	<b><?= $sourcename ?></b><br>
	<?php if (empty($alertset)): echo "No alerts"; else:
		foreach ($alertset as $alert) : ?>
		<div class='inleft2' border-top=1px solid black;'>
			<p><?=$alert['category'] ?? '' ?> <?=$alert['event']?></p>
			<p>Description: <?=$alert['description']?></p>
			<p>Instructions: <br>
				<?=$alert['instructions'] ?? '' ?></p>
			<p>Expires <?= $alert['expires']?></p>
		</div>
	<?php endforeach; ?>
	<?php endif; ?>
<?php endforeach; ?>
	<hr style="height:4px;background-color:green;">
</div>


<p>You can have up to two alerts.<br>
Title will be displayed in red.  To remove alert, remove the title.<br>
Alerts must have expiration.  If today, just enter the time.  Otherwise enter month/day and time. Day without time means 12:01am</p>
<h5>Alert A</h5>
<table class='border no-col'>
<tr class='left'><td>Headline</td>
	<td><input type='text' value="<?=$admin['alertA']['title'] ??'' ?> " name="alertA[title]" size='45'></td></tr>
<tr class='left'><td>Information:</td><td> <textarea name="alertA[text]" rows='2' cols='80'><?=$admin['alertA']['text']??''?></textarea></td></tr>
<tr class='left'><td>Expires</td><td><input type='text' name="alertA[expires]" value="<?=$admin['alertA']['expires']??''?>" >
</td></tr>
</table>

<h5>Alert B</h5>
<table class='border no-col'>
<tr class='left'><td>Headline</td>
	<td><input type='text' value="<?=$admin['alertB']['title'] ??'' ?> " name="alertB[title]" size='45'></td></tr>
<tr class='left'><td>Information:</td><td> <textarea name="alertB[text]" rows='2' cols='80'><?=$admin['alertB']['text']??''?></textarea></td></tr>
<tr class='left'><td>Expires</td><td><input type='text' name="alertB[expires]" value="<?=$admin['alertB']['expires']??''?>" >
</td></tr>
</table>


<h4>Enter closures/announcements</h4>
One announcement per line. They will be listed as bullets<br />
<textarea name='announcements' ><?=$admin['announcements'] ?></textarea>


<h4>Enter visitor advice</h4>
One announcement per line.(<cr>)  They will be listed as bullets<br />
<textarea name='advice' ><?=$admin['advice'] ?></textarea>


<h4>Enter fire status</h4>
<p>General Fire Level: <select name='fire_level'><?=$admin['fire_level_options']?></select>
</p>



<h4>Campground status</h4>


<!--
<p><input type='checkbox' name='cgfull'
> Check to force all campgrounds full until unset.</p>
 -->
<p>
Enter update to available sites.  No entry means keep current value.
Reservation sites updated (not implemented yet) from rec.gov hourly.
</p>

<p>
Uncertainty.  <input type='number' name='uncertainty' size='4' value="<?=$admin['uncertainty'] ?? 0 ?>" min=0 max=12 > Enter number of hours the new site vacancy setting is valid.  Will be displayed to users  as '?' after the time has lapsed.
</p>

<table>
<tr><th>Campground</th><th>Status</th><th>Open Sites</th><th>Update</th><th>Notes</th></tr>
<?php foreach (array_keys(Defs::$campsites) as $scode): ?>
	<tr><td><?= Defs::$sitenames[$scode] ?></td>

		<td><select name="cgstatus[<?=$scode?>]"><?=$admin['cg_options'][$scode]?></select></td>
		<td><?=$admin['cgsites'][$scode]?> </td>
		<td> <!--
<input type='text' name="cgopen[<?=$scode?>]"
			 size='8' class ='cgo'>
 -->
			 <select name="cgupdate[<?=$scode?>]" class='cgo'><?=$open_options?></select></td>

		<td><input type='text' name="cgnotes[<?=$scode?>]>"
		<?php if (isset($admin['cgnotes'])) : ?>
		value='<?=$admin['cgnotes'][$scode]?>' <?php endif; ?>
		size=40>
		</td>
	</tr>
<?php endforeach; ?>
</table>
Click to <button type='button' onClick='clearopen()'> clear all site updates</button> (clear = no change) <br />
<?php $this->insert('cal-admin',['calendar'=>$calendar]); ?>

<hr>
<h4>Choose Pages for TV Rotation</h4>
<?php //u\echor($admin); ?>
Select which pages should appear in the rotation sequence (?snap)<br />
<?php foreach (Defs::$rpages as $pid=>$pdesc) : ?>
<input type='checkbox' name='rotate[]' value='<?=$pid?>' <?=$admin['rchecked'][$pid] ?? ''?> ><a href='/pager.php?<?=$pid?>' target = 'pager'><?=$pid?></a>: <?=$pdesc?><br />
<?php endforeach; ?>
<br />
Set rotation delay in seconds: <input type='number' name='rdelay' value='<?=$admin['rdelay']?>' size='8' min=10 max=30 step=5 >
<button type='submit'>Submit Form</button>

</form>
<hr>


</body>
</html>
