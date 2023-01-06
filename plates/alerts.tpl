<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php
//u\echor($admin,'admin');
$alerts = $admin['notices']['alerts'];
	if (!empty($alerts)):
		foreach ($alerts as $alert):
			echo $alert ;
		endforeach;
	elseif ($admin['alert_alt']):
?>
<div class='border center' style='margin-top:0.5em;width:80%'>
<?php echo nl2br($admin['alert_alt']) ?>
</div>

<?php endif; ?>
