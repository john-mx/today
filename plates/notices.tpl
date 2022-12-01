<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php if (!empty ($d= $notices['alerts'])) : ?>
<div class='in2 float' style='width:45%;'>
<h4 class='black'>Active Alerts:</h4>

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
<?php endif; ?>

<?php if(!empty($d = $notices['announcements'])) : ?>
<div class='in2 float' style='width:45%;'>
<h4>Announcements</h4>
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
<?php endif; ?>

<div class='clear'></div>
