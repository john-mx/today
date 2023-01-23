<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

$e = $admin['advice'] ?? '';

?>





<h3>Today's Recommendations for Visitors</h3>
<div class='indent2 left'>
	<ul>
<?php if ($e) :

		$anlist = explode("\n",$e);
//		Utilities::echor($anlist,'anlist');
			foreach ($anlist as $item):
				if (empty(trim($item))):continue;endif;
				echo "<li>$item</li>";
			endforeach;
			// echo "<br /><li>Please: <span class='red'>Do Not Die Today!</span> Be Safe.</li>";
		?>
<?php else: ?>
<li>None at this time
		<?php endif; ?>
		</ul>

</div>



