<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php if (!empty ($d= $notices['alerts'])) : ?>
<div class='float'>
<h4 class='black'>Active Alerts:</h4>
<div class='warn'>
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

