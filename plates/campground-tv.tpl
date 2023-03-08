<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;


if(empty($camps)): echo "No Campground Data"; exit;
endif;


?>

<h3>Campgrounds
<?php if ($camps['total_open'] == 0) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h3>

<?php
	$cgs = LS::getCampCodes();
	sort ($cgs);

?>

<div  style='vertical-align:top' >

<?php $status = 'Reserved';
$no_entries = true; ?>
<div class='inlineblock width45'>
<table >
	<tr class='bg-orange '><td colspan='4' ><b>Reserved Campsites</b></td></tr>

	<tr ><th>Campground</th>
		<th>Sites</th>
		<th style='width:4em;'>Fee</th>
		<th>Available Now</th></tr>
		<?php foreach ($cgs as $cg) : ?>
			 <?php if ($camps['cgs'][$cg]['status'] == $status):
				$no_entries=false;
				$staletag = Defs::getStaleTag($camps['cgs'][$cg]['stale']);
				$typecolor = ($camps['cgs'][$cg]['stale'] == 0)? 'dk-green':'black';
				?>
				<tr class='border-bottom'>
				<td class='left'>  <?=LS::getLocName($cg) ?>  </td>
				<td > <?= LS::getCampSites($cg) ?> </td>
				<td> $&nbsp;<?= LS::getCampfee($cg) ?> </td>
				<td class='<?=$typecolor?>'><b>
				<?= $camps['cgs'][$cg] ['open']?> <?=$staletag?></b>

						</td>

				</tr>
			<?php  endif; ?>

	<?php endforeach;?>
	<?php if ($no_entries):?>
			<tr><td colspan='4' class='left'>None</td></tr>
	<?php endif; ?>
</table>


</div>


<?php $status = 'First';
$no_entries = true; ?>
<div class='width45 inlineblock' style='vertical-align:top;'>
<table >
<tr class='bg-orange left'><td colspan='4' ><b>First-Come, First-Served Campgrounds</b></td></tr>

	<tr ><th>Campground</th><th>Sites</th><th style='width:4em;'>Fee</th><th>Available Now</th></tr>
		<?php foreach ($cgs as $cg) : ?>
			 <?php if ($camps['cgs'][$cg]['status'] == $status):
				$no_entries=false;
				$staletag = Defs::getStaleTag($camps['cgs'][$cg]['stale']);
				$typecolor = ($camps['cgs'][$cg]['stale'] == 0)? 'dk-green':'black';
				?>
				<tr class='border-bottom'>
				<td class='left'>  <?=LS::getLocName($cg) ?>  </td>
				<td > <?= LS::getCampSites($cg) ?> </td>
				<td> $&nbsp;<?= LS::getCampfee($cg) ?> </td>
				<td class='<?=$typecolor?>'><b><?= $camps['cgs'][$cg] ['open']?> <?=$staletag?></b>

						</td>

				</tr>
			<?php  endif; ?>

	<?php endforeach;?>
		<?php if ($no_entries):?>
			<tr><td colspan='4' class='left'>None</td></tr>
		<?php endif; ?>
</table>

<?php $status = 'Closed';
$no_entries = true;
$closed = [];
?>
<br/>
<div class='left'>
<b>Closed Campgrounds: </b>
		<?php foreach ($cgs as $cg) : ?>
			 <?php if ($camps['cgs'][$cg]['status'] == $status):
				$no_entries=false;
				$closed[]= LS::getLocName($cg);
				?>
			<?php  endif; ?>
	<?php endforeach;?>
	<?php if (! $closed):
			echo "None";
			else:
			echo join (',',$closed);
			endif;
		?>
</div>

</div>
</div>
<div>
<p class='center'>Recreation.gov availability updated at <?=$camps['rec.gov_update']?>
<br />

Data is less than 1 hour old unless tagged.<br />
<?=Defs::getStaleTag(1)?>: Up to 12 hours old.
<?=Defs::getStaleTag(2)?>: Old data; not reliable.
</p>
</div>
</div>


