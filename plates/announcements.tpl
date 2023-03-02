<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



?>



<?php if(!empty($d = $notices['announcements'])) : ?>
<div class='float'>
<h3>Announcements</h3>
<div>
	<?php
		echo "<ul>";
		$anlist = explode("\n",$d);
			foreach ($anlist as $item) :
				if (empty(trim($item))):continue;endif;
				echo "<li>$item</li>";
			endforeach;
		echo "</ul>" . NL;
	?>
	</div>
	</div>
<?php endif; ?>


