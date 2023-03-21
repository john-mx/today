<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheSettings as CS;
use DigitalMx\jotr\LocationSettings as LS;

?>
<div>The admin page has a number of sections, but it is really one big long form.  There are Submit buttons in several places, but they are all the same: they all submit the entire form, including the campground and calendar, not just the part near the button.
</div>


<input type='hidden' name='type' value="update">
<h4>Say something pithy</h4>
	<input name='pithy' type='text' size='80' value="<?= $this->e($admin['pithy']) ?>"><br>
	(Appears at top of Today report and Summary, not on TV)
</p>



<h4>Enter alert <?php U::showHelp('alerts');?></h4>
<p>Enter the alert you wish to display.  Click <button type='button' onClick = "showDiv('galerts');"> View Active Alerts</button> to view active alerts from weather.gov. Copy and edit as appropriate. Alert will be displayed until the expiration time.</p>

<div id='galerts' class='hidden'>
	Weather Service alerts below:<br>
	The expiration on our alert should (probably) be the "ends"
	time below.
	<?php

		foreach ($galerts['galerts'] as $loc=>$alertset) :

//U::echor($galerts,'galerts');
		//$alertset = $galerts['galerts']['galerts'][$source];

		//Utilities::echor($alertset,$source);
		$locname = LS::getLocName($loc);
	?>
	<hr style="height:4px;background-color:green;">
	<b><?= $locname ?></b><br>
	<?php if (empty($alertset)): echo "No alerts"; else:
		foreach ($alertset as $alert) : ?>
		<div class='inleft2 border'>
		<p><b><?=$alert['event'] ?? '' ?> for <?=$alert['areaDesc']?></b></p>

			<?=$alert['headline']?></p>
			<p>Description: <?=$alert['description']?></p>
			<p>Onset: <?=$alert['onset']?></p>
			<p>Ends: <?=$alert['ends']?> (<?= $alert['ends-short']?>) </p>
			<p>(alert Expires <?= $alert['expires']?> )</p>
		</div>
	<?php endforeach;
	endif;
endforeach;
?>
	<hr style="height:4px;background-color:green;">
</div>


<p>You can have only one alert.<br>
Alerts must have expiration date and time. Time without day is today. Day without time is 12:01am.  You can put in "Feb 14 3 pm" or "tomorrow" or you can copy and paste the expiration on government alerts, like "2023-01-14T22:00:00-08:00".</p>

<table class='border no-col'>
<tr class='left'><td>Headline</td>
	<td><input type='text' value="<?=$admin['alertA']['title'] ??'' ?> " name="alertA[title]" size='45'></td></tr>
<tr class='left'><td>Information:</td><td> <textarea name="alertA[text]" rows='2' cols='80'><?=$admin['alertA']['text']??''?></textarea></td></tr>
<tr class='left'><td>Expires</td><td><input type='text' name="alertA[expires]" value="<?=$admin['alertA']['expires']??''?>" >
</td></tr>
</table>
<button class='submit' type='submit'>Submit Form</button>


<h4>Enter closures/announcements  <?php U::showHelp('notices');?></h4>
One announcement per line. They will be listed as bullets <br />

<textarea name='announcements' rows='6' cols='80'><?=$admin['announcements'] ?></textarea>


<h4>Enter safety advice <?php U::showHelp('advice');?></h4>
One announcement per line(carriage return). Rotating pages and summary page show 1 or 2 random items from the list.<br />

<textarea name='advice' rows='6' ><?=$admin['advice'] ?></textarea>
<br />

<h4>Enter fire status</h4>
<p>General Fire Level: <select name='fire_level'><?=$admin['fire_level_options']?></select>
</p>
<button class='submit' type='submit'>Submit Form</button>


<h4>Campground status </h4>
Click <button type='button' onClick = "showDiv('camps');">Campground Admin</button> to open/hide campground information.

<div id='camps' class='hidden'>
<?php $this->insert('camp-admin',['camps'=>$camps]); ?>
</div>


<h4>Calendar </h4>
Click <button type='button' onClick = "showDiv('cal');">Calendar Admin</button> to open/hide calendar entries.
<div id='cal' class='hidden'>
<?php $this->insert('cal-admin',['calendar'=>$calendar]); ?>
</div>


