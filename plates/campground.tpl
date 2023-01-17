<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



?>



<?php if(empty($camps)): echo "No Campground Data"; else: ?>
<h4>Campgrounds
<?php if (!empty($camps['cgfull'] ?? '')) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h4>

<table  class='alt-gray border center'>
<tr ><th>Campground</th><th>Sites</th><th>Nightly Fee</th>
<th>Open Sites</th>
<th style='border-right:2px solid black;'>Note</th>
</tr>
<?php
	$cgs = array_keys(Defs::$campsites);
	sort ($cgs);
	foreach (['Reservation','First','Closed'] as $status):
		$no_entries = true; // track for no entries
	?>

<?php if ($status == 'Reserved'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>Reserved Campgrounds</b> Make reservations at rec.gov or call 1-877-444-6777. <br />
	 Available sites </td></tr>

<?php elseif ($status == 'First'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>First Come, First Served Campgrounds</b> Find an empty site and claim it. Pay ranger or at entrance station. <br />
			Available sites </td></tr>

<?php elseif ($status == 'Closed'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>Closed Campgrounds</b></td></tr>
<?php endif; ?>

			<?php foreach ($cgs as $cg) : ?>
			 <?php if ($camps[$cg]['status'] == $status):
				$no_entries=false;
				?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td> $<?= Defs::$campfees[$cg] ?> </td>
				<!-- <td><?= $camps['sites'][$cg] ?> </td> -->
				<td> <?= $camps[$cg]['notes'] ?>  </td>
				</tr>
			<?php  endif; ?>

	<?php endforeach;?>
	<?php if ($no_entries): ?>
			<tr><td colspan='5' class='left'>None</td>
			</tr>
			<?php endif; ?>
<?php endforeach; ?>

</table>


<div class='center' style='padding:1em;'><image src='/images/Digital-Cgres-Badge.png' style='height:120px;' alt='https://www.recreation.gov/search?q=Joshua%20Tree%20National%20Park&entity_id=2782&entity_type=recarea&inventory_type=camping&inventory_type=dayuse&parent_asset_id=2782'> </div>

<?php endif; ?>

