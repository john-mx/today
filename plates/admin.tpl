<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheSettings as CS;
use DigitalMx\jotr\LocationSettings as LS;

?>

<hr>
<h3>Admin only items</h3>
<h4>Alert Alternative </h4>
Enter message here to display if there are no alerts. HtML is allowed here, so ask for help if needed.  Carriage returns are displayed as new lines.  <br />
Example: for color red: &lt;span style='color:red;'&gt;text you want &lt;/span&gt;
<br />See help button for more help.<br />
<textarea name = 'alert_alt' rows='4' cols='80'><?=$admin['alert_alt'] ??'' ?></textarea>


<h4>Choose Pages for TV Rotation <?php U::showHelp('rotation');?></h4>

Select which pages should appear in the rotation sequence (?snap)<br />(These are default settings.  Each individual display can set their own display preferences.)<br />
<?php foreach (LS::getRpageArray() as $pid=>$pdesc) : ?>
<input type='checkbox' name='rotate[]' value="<?=$pid?>" <?=$admin['rchecked'][$pid] ?? ''?> >&nbsp;&nbsp;<a href="/pager.php?<?=$pid?>" target = 'pager'><?=$pid?></a>: <?=$pdesc?><br />
<?php endforeach; ?>
<br />
Set rotation delay in seconds: <input type='number' name='rdelay' value="<?=$admin['rdelay']?>" size='8' min=10 max=30 step=5 >

<p>
<button class='submit' type='submit'>Submit Form</button>
</p>


