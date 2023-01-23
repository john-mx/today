<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



?>
<div class='alert' >
<?php
//U::echor($admin['notices']);
//Utilities::echor($admin,'admin');


	if(
		! isset($admin['notices']['alert'])
		|| empty($admin['notices']['alert']['title'])
		|| $admin['notices']['alert']['expires'] < time()
		):
			echo  nl2br($admin['alert_alt']);
	else:
		$alert = $admin['notices']['alert'];
		$expire_date = date('M d g:i a',$alert['expires']);
	?>
		<div class='red inlineblock center width100 larger'><b><?=$alert['title']?></b> </div> <br />
		<div class='inline-block indent width100 center'>
			<?php echo $this->escape($alert['text']);?>
		</div>
		<div class='inlineblock right width100' style='font-weight:normal;'><small>Expires: <?=$expire_date?></small></div>
	<?php endif; ?>
</div>
