<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



?>

<?php
//U::echor($admin['notices']);
//Utilities::echor($admin,'admin');


	if(
		! isset($admin['notices']['alert'])
		|| empty($admin['notices']['alert']['title'])
		|| $admin['notices']['alert']['expires'] < time()
		):
		$altalert = nl2br($admin['alert_alt']);
		?>
		<div class='alert middle center ' style='border:1px solid black'>
			<?=$altalert?>
		</div>
	<?php else:
		$alert = $admin['notices']['alert'];
		$expire_date = date('M d g:i a',$alert['expires']);
	?>
	<div class='alert middle center ' >
		<div class='red inlineblock larger'>
		<b><?=$alert['title']?></b> </div> <br />
		<div class='inline-block width80  middle center'>
			<?php echo $this->escape($alert['text']);?>
		</div>
		<div class=' right ' style='font-weight:normal;'><small>Expires: <?=$expire_date?></small></div>
		</div>
	<?php endif; ?>
