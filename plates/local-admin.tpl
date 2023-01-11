<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;




?>

<form method='post'>



<h4>Local Settings</h4>
Information entered here will affect only the site they are entered on, and will reset the next day.
<h5>announcements</h5>
Current park announcements listed below:
<?php
 $d = explode("\n",$admin['announcements']);
 if ($d):
 echo "<ul>";
 	foreach ($d as $item):
 		echo "<li>" . $item;
 	endforeach;
 echo "</ul>";
 endif;
?>
Add local announcements here.
One announcement per line. They will be listed as bullets at this site only.<br />
<textarea name='announcements' ><?=$local['announcements'] ?></textarea>


<h4>Enter visitor advice</h4>
One announcement per line.(<cr>)  They will be listed as bullets<br />
<textarea name='advice' ><?=$admin['advice'] ?></textarea>


<hr>
<h4>Choose Pages for TV Rotation</h4>
<?php //Utilities::echor($admin); ?>
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
