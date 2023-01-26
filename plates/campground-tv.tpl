<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


if(empty($camps)): echo "No Campground Data"; exit;
endif;

$cgs = array_keys(Defs::$campsites);
	sort ($cgs);
$total_open = 0;
foreach ($cgs as $cg):
	if ((time() - $camps['cgs'][$cg]['asof'])  < 3*60*60): $stale[$cg] = '#3F3';
	elseif ((time() - $camps['cgs'][$cg]['asof'])  < 12*60*60): $stale[$cg] = '#FF3';
	else: $stale[$cg] = '#F33';
	endif;
	if ($camps['cgs'][$cg]['status'] == 'Closed'):
					$stale[$cg] = '#FFF';
					$camps['cgs'][$cg]['open'] = 0;
	endif;
	if ($camps['cgs'][$cg]['open'] > 0):++$total_open;endif;
	endforeach;
?>

<h3>Campgrounds
<?php if ($total_open == 0) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h3>

<?php
	$cgs = array_keys(Defs::$campsites);
	sort ($cgs);
?>
<div>Open Sites updated frequently. Green = reported in last 3 hours; yellow = last 12 hours; red = older. Reserved sites from Recreation.gov update at <?=$camps['updated']?> </div>
<div class ='center ' style='vertical-align:top' >

<?php $status = 'Reserved';
$no_entries = true; ?>

<table class='alt-gray width45 inlineblock'>
	<tr class='bg-orange '><td colspan='4' ><b>Reserved Campsites</b></td></tr>
		<tr><td colspan='4' class='left'>Reserve site at rec.gov or call (877) 444-6777 or from outside US +1 (606) 515-6777.<br />

		</td></tr>
	<tr ><th>Campground</th>
		<th>Sites</th>
		<th style='width:4em;'>Fee</th>
		<th>Open Sites</th></tr>
	<?php foreach ($cgs as $cg) :
		if ($camps['cgs'][$cg]['status'] == $status) :
				$no_entries=false; ?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td> $&nbsp;<?= Defs::$campfees[$cg] ?> </td>

				<td style='background-color:<?=$stale[$cg]?>' ><?= $camps['cgs'][$cg]['open'] ?> </td>

				</tr>
		<?php endif ?>
	<?php endforeach;?>
	<?php if ($no_entries):?>
			<tr><td colspan='4' class='left'>None</td></tr>
	<?php endif; ?>
</table>



<?php $status = 'First';
$no_entries = true; ?>
<div class='width45 inlineblock' style='vertical-align:top';>
<table class='alt-gray' >
<tr class='bg-orange left'><td colspan='4' ><b>First Come, First Served Campgrounds</b></td></tr>
		<tr><td colspan='4'  class='left'>Find a site and claim it.  Pay ranger or at entrance station.<br />

		</td></tr>
	<tr ><th>Campground</th><th>Sites</th><th style='width:4em;'>Fee</th><th>Open Sites</th></tr>
	<?php foreach ($cgs as $cg) :
		if ($camps['cgs'][$cg]['status'] == $status) :
				$no_entries=false; ?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td> $&nbsp;<?= Defs::$campfees[$cg] ?> </td>

				<td style='background-color:<?=$stale[$cg]?>' ><?= $camps['cgs'][$cg]['open'] ?> </td>

				</tr>
			<?php endif ?>
		<?php endforeach;?>
		<?php if ($no_entries):?>
			<tr><td colspan='4' class='left'>None</td></tr>
		<?php endif; ?>
</table>

<?php $status = 'Closed';
$no_entries = true; ?>
<table class='alt-gray width45' style='margin-top:1em;'>
<tr class='bg-orange '><td colspan='4' ><b>Closed Campgrounds</b></td></tr>

<tr ><th>Campground</th><th>Sites</th><th style='width:4em;'>Fee</th><th>Open SItes</th></tr>
	<?php foreach ($cgs as $cg) :
		if ($camps['cgs'][$cg]['status'] == $status) :
				$no_entries=false; ?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td>$&nbsp;<?= Defs::$campfees[$cg] ?> </td>

				<td>0 </td>

				</tr>
			<?php endif ?>
		<?php endforeach;?>
		<?php if ($no_entries):?>
			<tr><td colspan='4' class='left'>None</td></tr>
		<?php endif; ?>
</table>
</div>
<p><small>Recreation.gov availability updated at <?=$camps['updated']?></small></p>
</div>

