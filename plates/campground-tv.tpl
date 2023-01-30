<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


if(empty($camps)): echo "No Campground Data"; exit;
endif;


?>

<h3>Campgrounds
<?php if ($camps['total_open'] == 0) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h3>

<?php
	$cgs = array_keys(Defs::$campsites);
	sort ($cgs);
	$staletags = array(
		'0' => '',
		'1' => "<span class='green'><b>?</b></span>",
		'2' => "<span class='red'>⁉️</span>",
	);
?>
<div>Open Sites updated frequently. Tag after open number indicates age. No tag: less than 3 hours old.  <?=$staletags[1]?>: less than 12 hours old.  <?=$staletags[2]?>: older. and unreliable. Reserved sites from Recreation.gov update at <?=$camps['updated']?> </div>
<div class ='center ' style='vertical-align:top' >

<?php $status = 'Reserved';
$no_entries = true; ?>

<table class='alt-gray width45 inlineblock'>
	<tr class='bg-orange '><td colspan='4' ><b>Reserved Campsites</b></td></tr>
		<tr><td colspan='4' class='left'>Reserve site at recreation.gov or call (877) 444-6777 or from outside US +1 (606) 515-6777.<br />

		</td></tr>
	<tr ><th>Campground</th>
		<th>Sites</th>
		<th style='width:4em;'>Fee</th>
		<th>Open Sites</th></tr>
	<?php foreach ($cgs as $cg) :

		if ($camps['cgs'][$cg]['status'] == $status) :
				$staletag = $staletags[$camps['cgs'][$cg]['stale']];

				$no_entries=false; ?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td> $&nbsp;<?= Defs::$campfees[$cg] ?> </td>

				<td style='background-color:<?=$camps['cgs'][$cg]['stale']?>' ><?= $camps['cgs'][$cg]['open'] ?> <?=$staletag?></td>

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
				$staletag = $staletags[$camps['cgs'][$cg]['stale']];

				$no_entries=false; ?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td> $&nbsp;<?= Defs::$campfees[$cg] ?> </td>

				<td style='background-color:<?=$camps['cgs'][$cg]['stale']?>' ><?= $camps['cgs'][$cg]['open'] ?>
				<?=$staletag?></td>

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
			$staletag = $staletags[$camps['cgs'][$cg]['stale']];

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

