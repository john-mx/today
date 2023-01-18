<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



?>

<?php
//Utilities::echor($admin,'admin');
$alerts = $admin['notices']['alerts'];
	if (!empty($alerts)):
		foreach ($alerts as $alert):
			echo $alert ;
		endforeach;
	elseif ($admin['alert_alt']):
?>
<div class='border center' style='margin-top:0.5em;width:80% font-weight:500;'>
<?php echo nl2br($admin['alert_alt']) ?>
</div>

<?php endif; ?>
