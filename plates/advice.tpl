<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

$e = $admin['advice'] ?? '';
$animation ??= '';
?>
<h3>Today's Recommendations for Visitors</h3>
<?php if ($e) :
	$anlist = explode("\n",$e);
	//$fixed = $admin['fixedAdvice'];
	//$fixed = array_shift($anlist);
	$fixed = $anlist[rand(sizeof($anlist))];
	?>
	<div class='indent2 left'>
	<ul>
	<?php if ($animation !== 'snap'):
				foreach ($anlist as $item):
					if (empty(trim($item))):continue;endif;
					echo "<li>$item</li>";
				endforeach;
				echo "<li>$fixed</li>";
		else:
		?>
		<li><div id='randomAdvice'>Hike Safely!</div></li>
		<li><?= $fixed ?>
	<?php endif; ?>

		</ul>
<?php else: ?>
	None at this time
<?php endif; ?>
</div>



