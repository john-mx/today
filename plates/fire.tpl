<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>
<h4>Fire Information </h4>
<?php if(empty($fire)): echo "<p>No Data</p>"; else:?>

	 	<p style = 'width:100%;'>
	 	<b>Current Fire Level:</b>
	 	<span style="background-color:<?=$fire['color'] ?? '' ?>">
	 	<?=$fire['level']?> </span></p>
			<div class='left'>
				<?=Defs::$firewarn[$fire['level']]?>
			</div>
			<?php endif; ?>



