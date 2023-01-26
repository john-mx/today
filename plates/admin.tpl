<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheSettings as CS;



?>
<div>The admin page has a number of sections, but it is really one big long form.  There are Submit buttons in several places, but they are all the same: they all submit the entire form, not just the part near the button.
</div>

<div class='content'>
<form method='post'>
<input type='hidden' name='type' value='update'>
<h4>Say something pithy</h4>
	<input name='pithy' type='text' size='80' value='<?=$this->e($admin['pithy'])?>'>
</p>



<h4>Enter alert <?php U::showHelp('alerts');?></h4>
<p>Enter the alert you wish to display.  Click <button type='button' onClick = "showDiv('galerts');"> View Active Alerts</button> to view active alerts from weather.gov. Copy and edit as appropriate. </p>

<div id='galerts' class='hidden'>
	<?php
// 		foreach ($galerts as $source=>$alertset) :
		$source = 'wgovalerts';
		$alertset = $galerts[$source];

		//Utilities::echor($alertset,$source);
		$sourcename =CS::getSourceName($source);
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
	<?php endforeach;
	endif;
//endforeach;
?>
	<hr style="height:4px;background-color:green;">
</div>


<p>You can have only one alert.<br>
Title will be displayed in red.  To remove alert, remove the title.<br>
Alerts must have expiration.  If today, just enter the time.  Otherwise enter month/day and time. Day without time means 12:01am. You can use the format shown on government alerts, e.g. 2023-01-14T22:00:00-08:00.</p>

<table class='border no-col'>
<tr class='left'><td>Headline</td>
	<td><input type='text' value="<?=$admin['alertA']['title'] ??'' ?> " name="alertA[title]" size='45'></td></tr>
<tr class='left'><td>Information:</td><td> <textarea name="alertA[text]" rows='2' cols='80'><?=$admin['alertA']['text']??''?></textarea></td></tr>
<tr class='left'><td>Expires</td><td><input type='text' name="alertA[expires]" value="<?=$admin['alertA']['expires']??''?>" >
</td></tr>
</table>
<button class='submit' type='submit'>Submit Form</button>
<!--
<h5>Alert B</h5>
<table class='border no-col'>
<tr class='left'><td>Headline</td>
	<td><input type='text' value="<?=$admin['alertB']['title'] ??'' ?> " name="alertB[title]" size='45'></td></tr>
<tr class='left'><td>Information:</td><td> <textarea name="alertB[text]" rows='2' cols='80'><?=$admin['alertB']['text']??''?></textarea></td></tr>
<tr class='left'><td>Expires</td><td><input type='text' name="alertB[expires]" value="<?=$admin['alertB']['expires']??''?>" >
</td></tr>
</table>
 -->
<h4>Alert Alternative </h4>
Enter message here to display if there are no alerts. HtML is allowed here, so ask for help if needed.  Carriage returns are displayed as new lines.  <br />
Example: for color red: &lt;span style='color:red;'&gt;text you want &lt;/span&gt;
<br />See help button for more help.<br />
<textarea name = 'alert_alt' rows='4' cols='80'><?=$admin['alert_alt'] ??'' ?></textarea>


<h4>Enter closures/announcements  <?php U::showHelp('notices');?></h4>
One announcement per line. They will be listed as bullets <br />

<textarea name='announcements' rows='6' cols='80'><?=$admin['announcements'] ?></textarea>


<h4>Enter visitor advice <?php U::showHelp('advice');?></h4>
One announcement per line(carriage return). <br />
<textarea name='advice' rows='6' ><?=$admin['advice'] ?></textarea>


<h4>Enter fire status</h4>
<p>General Fire Level: <select name='fire_level'><?=$admin['fire_level_options']?></select>
</p>
<button class='submit' type='submit'>Submit Form</button>


<h4>Campground status <?php U::showHelp('campsites');?></h4>
Click <button type='button' onClick = "showDiv('camps');">Campground Admin</button> to update campground information.

<div id='camps' class='hidden'>
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
Uncertainty.  <input type='number' name='uncertainty' size='4' value="<?=$admin['uncertainty'] ?? 0 ?>" min=0 max=12 > Enter number of hours the new site vacancy setting is valid.  Will be displayed to users  as '?' after the time has lapsed.
</p>
 -->

<table>
<tr><th>Campground</th><th>Status</th><th>Open Sites</th><th>As Of</th><th>Update</th><th>Notes</th></tr>

<?php $keys = array_keys($camps);
	sort ($keys);
	foreach ($keys as $scode): ?>
	<tr><td><?= Defs::$sitenames[$scode] ?></td>

		<td><select name="camps[<?=$scode?>][status]"><?=$camps[$scode]['statusopt']?></select></td>
		<!-- <td><?=Defs::$campsites[$scode]?> </td> -->
		<td><?=$camps[$scode]['open'] ?></td>
		<td><?=$camps[$scode]['asofHM']?></td>
		<td>
			 <!-- <select name="cgupdate[<?=$scode?>]['update']" class='cgo'><?=$open_options?></select></td> -->
<input type=text name="camps[<?=$scode?>][cgupdate]" class='cgo' size='8' value = ''></td>
		<td><input type='text' name="camps[<?=$scode?>][notes]>"
		<?php if (isset($camps[$scode]['notes'])) : ?>
		value='<?=$camps[$scode]['notes'] ?>' <?php endif; ?>
		size=40>
		</td>
	</tr>
<?php endforeach; ?>
</table>
Click to <button type='button' onClick='clearopen()'> clear all site updates</button> (clear = no change)
<br />
<button class='submit' type='submit'>Submit Form</button>
</div>


<h4>Calendar <?php U::showHelp('calendar');?></h4>
Click <button type='button' onClick = "showDiv('cal');">Calendar Admin</button> to update calendar entries.
<div id='cal' class='hidden'>
<?php $this->insert('cal-admin',['calendar'=>$calendar]); ?>
<button class='submit' type='submit'>Submit Form</button>
</div>
<hr>
<h4>Choose Pages for TV Rotation <?php U::showHelp('rotation');?></h4>

Select which pages should appear in the rotation sequence (?snap)<br />(These are default settings.  Each individual display can set their own display preferences.)<br />
<?php foreach (Defs::$rpages as $pid=>$pdesc) : ?>
<input type='checkbox' name='rotate[]' value='<?=$pid?>' <?=$admin['rchecked'][$pid] ?? ''?> >&nbsp;&nbsp;<a href='/pager.php?<?=$pid?>' target = 'pager'><?=$pid?></a>: <?=$pdesc?><br />
<?php endforeach; ?>
<br />
Set rotation delay in seconds: <input type='number' name='rdelay' value='<?=$admin['rdelay']?>' size='8' min=10 max=30 step=5 >

<p>
<button class='submit' type='submit'>Submit Form</button>
</p>


</form>
</div>
<hr>


</body>
</html>
