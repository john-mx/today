<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php
$alerts = $admin['notices']['alerts'];
	if (!empty($alerts)):
		foreach ($alerts as $alert):
			echo $alert ;
		endforeach;
	else:
?>
<div class='border center' style='margin-top:0.5em;'>
<b>No Food</b> &bull; <b>No Water</b> &bull; <b>No Cell Service</b>  in the Park. <br>
<p >Be Prepared: <span class='red' >Do Not Die Today!</span></p>
</div>
<?php endif; ?>
