<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>



<?php if(!empty($d = $notices['announcements'])) : ?>
<div class='float'>
<h4>Announcements</h4>
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

