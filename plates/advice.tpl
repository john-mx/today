<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



?>





<h3>Today's Recommendations for Visitors</h3>
<div class='indent2 warn'>

<?php
	$d= $admin['advice'] ?? '';
		echo "<ul>";
		$anlist = explode("\n",$d);
//		Utilities::echor($anlist,'anlist');
			foreach ($anlist as $item):

				if (empty(trim($item))):continue;endif;
				echo "<li>$item</li>";
			endforeach;
			// echo "<br /><li>Please: <span class='red'>Do Not Die Today!</span> Be Safe.</li>";
		echo "</ul>" . NL;
	?>
</div>



