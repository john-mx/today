<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

$asof =  date('M d g:i a', $camps['asof']);
?>



<?php if(empty($camps)): echo "No Campground Data"; else: ?>
<h4>Campgrounds
<?php if (!empty($camps['cgfull'])) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h4>
Open Sites as of <?= $asof ?>
<table  class='inleft2 alt-gray border'>
<tr ><th>Campground</th><th>Sites</th><th>Fee</th>
<th>Open Sites</th><th style='border-right:2px solid black;'>Note</th></tr>
<?php
	$cgs = array_keys(Defs::$campsites);
	sort ($cgs);
	foreach (['Reservation','First','Closed'] as $status):
	if ($status == 'Reservation') :
	?>
	<tr class='bg-orange left'><td colspan='5' ><b>Reserved Campgrounds</b> Make reservations at rec.gov or call 1-877-444-6777. </td></tr>
	<?php elseif ($status=='First') : ?>
		<tr class='bg-orange left'><td colspan='5' ><b>First Come, First Served Campgrounds</b> Find an empty site and claim it. Pay ranger or at entrance station.</td></tr>
	<?php elseif ($status=='Closed') : ?>
			<tr class='bg-orange left'><td colspan='5' ><b>Closed Campgrounds</b></td></tr>
	<?php endif;?>

	<?php
		$cgs = array_keys(Defs::$campsites);
		$nada=true;
		foreach ($cgs as $cg) : ?>
			<?php if ($camps['cg_status'][$cg] == $status):
				$nada=false;?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td>$ <?= Defs::$campfees[$cg] ?> </td>
				<td><?= $camps['cg_open'][$cg] ?> </td>
				<td> <?= $camps['cg_notes'][$cg] ?>  </td>
				</tr>
			<?php  endif; ?>
	<?php endforeach;?>
	<?php if ($nada):?>
		<tr><td colspan='5' class='left'>None</td></tr>
	<?php endif; ?>
<?php endforeach; //status ?>

</table>
<?php endif; ?>

