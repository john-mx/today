<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>



<h4 class='black'>Ranger Recommendations</h4>
<?php if (empty ($d= $advice)) :
	echo "<p class='in2'>No Recommendations</p>";
	else:?>
<div class='indent2 warn'>

<?php
		echo "<ul>";
		$anlist = explode("\n",$d);
			foreach ($anlist as $item) :
				if (empty(trim($item))):continue;endif;
				echo "<li>$item</li>";
			endforeach;
			echo "<br /><li>And finally: <span class='red'>Do Not Die Today!</span> Be Safe.</li>";
		echo "</ul>" . NL;
	?>
</div>

<?php endif; ?>


