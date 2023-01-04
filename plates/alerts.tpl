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
<div class='border center' style='margin-top:0.5em;width:80%'>
In The Park...
<span class='dk-orange'><b>No Food</b> &bull; <b>No Water</b> &bull; <b>No Cell Service</b>  &bull; <b>No Dogs on Hiking Trails</b></span><br>
<p class='dk-green'>Be Wise.  Be Safe. Do Not Die Today!</p>
</div>
<?php endif; ?>
