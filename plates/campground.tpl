<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

$cgopen_asof =  date('M d g:i a', $camps['cgopen_age']);
$cgres_asof =  date('M d g:i a', $camps['cgres_age']);
?>



<?php if(empty($camps)): echo "No Campground Data"; else: ?>
<h4>Campgrounds
<?php if (!empty($camps['cgfull'])) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h4>

<table  class='inleft2 alt-gray border'>
<tr ><th>Campground</th><th>Sites</th><th>Fee</th>
<th>Open Sites</th><th style='border-right:2px solid black;'>Note</th></tr>
<?php
	$cgs = array_keys(Defs::$campsites);
	sort ($cgs);
	foreach (['Reservation','First','Closed'] as $status):
		$no_entries = true; // track for no entries
	?>

<?php if ($status == 'Reservation'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>Reserved Campgrounds</b> Make reservations at rec.gov or call 1-877-444-6777. <br />
	 Available sites as of <?=$cgres_asof ?></td></tr>

<?php elseif ($status == 'First'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>First Come, First Served Campgrounds</b> Find an empty site and claim it. Pay ranger or at entrance station. <br />
			Available sites as of <?=$cgopen_asof?></td></tr>

<?php elseif ($status == 'Closed'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>Closed Campgrounds</b></td></tr>
<?php endif; ?>

			<?php foreach ($cgs as $cg) : ?>
			 <?php if ($camps['cg_status'][$cg] == $status):
				$no_entries=false;
				?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td> <?= Defs::$campfees[$cg] ?> </td>
				<td><?= $camps['sites'][$cg] ?> </td>
				<td> <?= $camps['cg_notes'][$cg] ?>  </td>
				</tr>
			<?php  endif; ?>

	<?php endforeach;?>
	<?php if ($no_entries): ?>
			<tr><td colspan='5' class='left'>None</td>
			</tr>
			<?php endif; ?>
<?php endforeach; ?>






</table>
<?php endif; ?>

