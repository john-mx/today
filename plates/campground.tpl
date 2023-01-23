<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


// set up stale flags
$cgs = array_keys(Defs::$campsites);
	sort ($cgs);

foreach ($cgs as $cg):
	if ((time() - $camps[$cg]['asof'])  > 24*60*60): $stale[$cg] = '#000';
	elseif ((time() - $camps[$cg]['asof'])  > 3*60*60): $stale[$cg] = '#FF0';
	else: $stale[$cg] = '#FFF';
	endif;
	if ($camps[$cg]['status'] == 'Closed'):
					$stale[$cg] = '#FFF';
					$camps[$cg]['open'] = 0;
	endif;
endforeach;
//U::echor($stale,'stale',NOSTOP);
?>



<?php if(empty($camps)): echo "No Campground Data"; else: ?>
<h3>Campgrounds
<?php if (!empty($camps['cgfull'] ?? '')) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h3>
<div class='inleft2'>Open Sites recorded every few hours.  If yellow, data is more than 3 hours old and not reliable.  If black, data is more than 12 hours old.</div>
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
	<tr class='bg-orange left'><td colspan='5' ><b>Reserved Campgrounds</b> Make reservations at rec.gov or call 1-877-444-6777. <br />
	 Available sites </td></tr>

<?php elseif ($status == 'First'): ?>
	<tr class='bg-orange left'><td colspan='5' ><b>First Come, First Served Campgrounds</b> Find an empty site and claim it. Pay ranger or at entrance station. <br />
			Available sites </td></tr>

<?php elseif ($status == 'Closed'):

	?>
	<tr class='bg-orange left'><td colspan='5' ><b>Closed Campgrounds</b></td></tr>
<?php endif; ?>

			<?php foreach ($cgs as $cg) : ?>
			 <?php if ($camps[$cg]['status'] == $status):
				$no_entries=false;

				?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td> $&nbsp;<?= Defs::$campfees[$cg] ?> </td>
				<td style='background-color:<?=$stale[$cg]?>'><?= $camps[$cg] ['open']?> </td>
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




<?php endif; ?>

