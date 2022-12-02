<?php

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<script>
function setopen(){
    var tObj = document.getElementsByClassName('cgo');
    for(var i = 0; i < tObj.length; i++){
        tObj[i].value='0';
    }
}

</script>


<h2>Today in Joshua Tree National Park</h2
<h3>Admin Page</h3>

<form method='post'>
<input type='hidden' name='type' value='update'>
<h4>Say something pithy</h4>
	<textarea name='pithy' rows='2' cols='80'><?=$this->e($admin['pithy'])?></textarea>
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
		<div class='in2' border-top=1px solid black;'>
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

<p>Enter alerts here. Each line of text (separated with a cr) will be a separate bulleted item.</p>
<textarea name='alerts'><?=$admin['alerts']?></textarea>
</p>


<h4>Enter closures/announcements</h4>
One announcement per line.(<cr>)  They will be listed as bullets<br />
<textarea name='announcements' ><?=$admin['announcements'] ?></textarea>


<h4>Enter visitor advice</h4>
One announcement per line.(<cr>)  They will be listed as bullets<br />
<textarea name='advice' ><?=$admin['advice'] ?></textarea>


<h4>Enter fire status</h4>
<p>General Fire Level: <select name='fire_level'><?=$admin['fire_level_options']?></select>
</p>



<h4>Campground status</h4>
<?php if($admin['cgfull']): ?>
	<p class='red'><b>ALL CAMPGROUNDS ARE FULL. </b></p>
<?php endif; ?>
<button type='button' onClick='setopen()'> All Sites Full</button>
<table>
<tr><th>Campground</th><th>Status</th><th>Open Sites</th><th>Notes</th></tr>
<?php foreach (array_keys($admin['cgstatus']) as $scode): ?>
	<tr><td><?= Defs::$sitenames[$scode]?></td>

		<td><select name="cgstatus[<?=$scode?>]"><?=$admin['cg_options'][$scode]?></select></td>
		<td><input type='text' name="cgopen[<?=$scode?>]"
			value='<?=$admin['cgopen'][$scode]?>' size='8' class ='cgo'>
		<td>

		<input type='text' name="cgnotes[<?=$scode?>]>"
		<?php if (isset($admin['cgnotes'])) : ?>
		value='<?=$admin['cgnotes'][$scode]?>' <?php endif; ?>
		size=40>
		</td>
	</tr>
<?php endforeach; ?>
</table>



<button type='submit'>Submit Form</button>

</form>
<h4>Calendar</h4>

<a href='/caladmin.php'>Update the Calendar</a>

</body>
</html>
