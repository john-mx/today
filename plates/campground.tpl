<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


// set up stale flags
$cgs = array_keys(Defs::$campsites);
	sort ($cgs);
$total_open = 0;
foreach ($cgs as $cg):
	if ((time() - $camps[$cg]['asof'])  < 3*60*60): $stale[$cg] = '#3F3';
	elseif ((time() - $camps[$cg]['asof'])  < 12*60*60): $stale[$cg] = '#FF3';
	else: $stale[$cg] = '#F33';
	endif;
	if ($camps[$cg]['status'] == 'Closed'):
					$stale[$cg] = '#FFF';
					$camps[$cg]['open'] = 0;
	endif;
	if ($camps[$cg]['open'] > 0):++$total_open;endif;
	endforeach;
//U::echor($stale,'stale',NOSTOP);
?>



<?php if(empty($camps)): echo "No Campground Data"; else: ?>
<h3>Campgrounds
<?php if ($total_open ==0 ):?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h3>
<div class='inleft2'>Open Sites checked several times a day.<br/>
If background is green, data is less than 3 hours old.  If yellow, data is less than 12 hours old and may be optimistic.  If red, data is more than 12 hours old and unreliable.</div>
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

