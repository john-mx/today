<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


// set up stale flags
$cgs = array_keys(Defs::$campsites);
	sort ($cgs);

	if(empty($camps)): echo "No Campground Data"; else:
?>
<h3>Campgrounds
<?php if ($camps['total_open'] ==0 ):?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h3>
<div class='inleft2'>Open Sites checked several times a day.Background indicates age.<br/>
Green: less than 3 hours old.  Yellow: less than 12 hours old.  Red: older. and unreliable.</div>
<table  class='alt-gray border center'>
<tr ><th>Campground</th><th>Sites</th><th>Nightly Fee</th>
<th>Open Sites</th>
<th style='border-right:2px solid black;'>Note</th>
</tr>
<?php
	foreach (['Reserved','First','Closed'] as $status):
		$no_entries = true; // track for no entries
	?>

<?php if ($status == 'Reserved'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>Reserved Campgrounds</b> Make reservations at rec.gov or call 1-877-444-6777. </td></tr>

<?php elseif ($status == 'First'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>First Come, First Served Campgrounds</b> Find an empty site and claim it. Pay ranger or at entrance station. </td></tr>

<?php elseif ($status == 'Closed'):

	?>
	<tr class='bg-orange left'><td colspan='5' ><b>Closed Campgrounds</b></td></tr>
<?php endif; ?>

			<?php foreach ($cgs as $cg) : ?>
			 <?php if ($camps['cgs'][$cg]['status'] == $status):
				$no_entries=false;

				?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td> $&nbsp;<?= Defs::$campfees[$cg] ?> </td>
				<td style='background-color:<?=$camps['cgs'][$cg]['stale']?>'><?= $camps['cgs'][$cg] ['open']?> </td>
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
<p><small>Recreation.gov availability updated at <?=$camps['updated']?></small></p>




<?php endif; ?>

