<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


?>
<h4>Fire Information </h4>
<?php if(empty($fire)): echo "<p>No Data</p>"; else:?>
	<hr style="border:10px solid <?=$fire['color'] ?>;">
	 	<p style = 'width:100%;'>
	 	<b> Fire Danger:</b>

	 	<?=$fire['level']?>
	 	</p>
	<p>Fires are permitted only in established campsites with fire rings</p>
			<?php endif; ?>



