<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;


// set up stale flags
$cgs = LS::getCampCodes();
	sort ($cgs);


	if(empty($camps)): echo "No Campground Data"; else:
?>
<h3>Campgrounds
<?php if ($camps['total_open'] ==0 ):?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h3>


<table  class='alt-gray border center'>
<tr ><th>Campground</th><th>Total Sites</th><th>Nightly Fee</th>
<th>Available Now</th>
<th style='border-right:2px solid black;'>Note</th>
</tr>
<?php
	foreach (['Reserved','First','Closed'] as $status):
		$no_entries = true; // track for no entries
	?>

<?php if ($status == 'Reserved'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>Reserved Campgrounds</b> Make reservations at recreation.gov or call 1-877-444-6777. </td></tr>

<?php elseif ($status == 'First'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>First Come, First Served Campgrounds</b> Find an empty site and claim it. Pay ranger or at entrance station. </td></tr>

<?php elseif ($status == 'Closed'):

	?>
	<tr class='bg-orange left'><td colspan='5' ><b>Closed Campgrounds</b></td></tr>
<?php endif; ?>

			<?php foreach ($cgs as $cg) : ?>
			 <?php if ($camps['cgs'][$cg]['status'] == $status):
				$no_entries=false;
				$staletag = Defs::getStaleTag($camps['cgs'][$cg]['stale']);
				$typecolor = ($camps['cgs'][$cg]['stale'] == 0)? 'dk-green':'black';

				?>
				<tr class='border-bottom'>
				<td class='left'>  <?=LS::getLocName($cg) ?>  </td>
				<td> <?= LS::getCampSites($cg) ?> </td>
				<td> $&nbsp;<?= LS::getCampfee($cg) ?> </td>
				<td class='<?=$typecolor?>'><b><?= $camps['cgs'][$cg] ['open']?> <?=$staletag?></b>

						</td>
				<td> <?= $camps['cgs'][$cg]['notes'] ?>  </td>
				</tr>
			<?php  endif; ?>

	<?php endforeach;?>
	<?php if ($no_entries): ?>
			<tr><td colspan='5' class='left'>None</td>
			</tr>
			<?php endif; ?>
<?php endforeach; ?>

</table>
<p class='center'>Recreation.gov availability updated at <?=$camps['rec.gov_update']?>
<br />

Tag after availability:
 <?=Defs::getStaleTag(1)?>: Data 3 to 12 hours old.  <?=Defs::getStaleTag(2)?>: older and unreliable.
</p>

<?php endif; ?>

