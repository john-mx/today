<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<h4>Fire Information </h4>
<?php if(empty($fire)): echo "<p>No Data</p>"; else:?>
	<hr style="border:10px solid <?=$fire['color'] ?>;">
	 	<p style = 'width:100%;'>
	 	<b> Fire Danger:</b>

	 	<?=$fire['level']?>
	 	</p>

			<div class='left'>
				<?=Defs::$firewarn[$fire['level']]?>
			</div>
			<?php endif; ?>



