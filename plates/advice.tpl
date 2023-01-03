<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>





<h4 class='black'>Ranger Recommendations</h4>
<div class='indent2 warn'>

<?php
	$d= $advice ?? '';
		echo "<ul>";
		$anlist = explode("\n",$d);
			foreach ($anlist as $item) :
				if (empty(trim($item))):continue;endif;
				echo "<li>$item</li>";
			endforeach;
			// echo "<br /><li>Please: <span class='red'>Do Not Die Today!</span> Be Safe.</li>";
		echo "</ul>" . NL;
	?>
</div>



