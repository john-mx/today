<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

if(empty($camps)): echo "No Campground Data"; exit;
endif;

$cgopen_asof =  date('M d g:i a', $camps['cgopen_age']);
$cgres_asof =  date('M d g:i a', $camps['cgres_age']);
?>

<h4>Campgrounds
<?php if (!empty($camps['cgfull'])) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h4>
<?php
	$cgs = array_keys(Defs::$campsites);
	sort ($cgs);
?>
<div>Open Sites updated when known.  ? = Information is stale and may not be reliable.</div>
<div class ='center ' style='vertical-align:top' >

<?php $status = 'Reservation';
$no_entries = true; ?>

<table class='alt-gray width45 inlineblock'>
	<tr class='bg-orange '><td colspan='4' ><b>Reserved Campsites</b></td></tr>
		<tr><td colspan='4' class='left'>Reserve site at rec.gov or call (877) 444-6777 or from outside US +1 (606) 515-6777.<br />
		Open sites as of <?=$cgres_asof?>
		</td></tr>
	<tr ><th>Campground</th>
		<th>Sites</th>
		<th style='width:4em;'>Fee</th>
		<th>Open Sites</th></tr>
	<?php foreach ($cgs as $cg) :
		if ($camps['cg_status'][$cg] == $status): ?>
				<?php $no_entries =false;?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td>$ <?= Defs::$campfees[$cg] ?> </td>

				<td><?= $camps['sites'][$cg] ?> </td>

				</tr>
		<?php  endif; ?>
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
		Open sites as of <?=$cgopen_asof?>
		</td></tr>
	<tr ><th>Campground</th><th>Sites</th><th style='width:4em;'>Fee</th><th>Open Sites</th></tr>
	<?php foreach ($cgs as $cg) :
			if ($camps['cg_status'][$cg] == $status): ?>
				<?php $no_entries =false;?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td>$ <?= Defs::$campfees[$cg] ?> </td>

				<td><?= $camps['sites'][$cg] ?> </td>

				</tr>
			<?php  endif; ?>
		<?php endforeach;?>
		<?php if ($no_entries):?>
			<tr><td colspan='4' class='left'>None</td></tr>
		<?php endif; ?>
</table>

<?php $status = 'Closed';
$no_entries = true; ?>
<table class='alt-gray' style='margin-top:1em;'>
<tr class='bg-orange '><td colspan='4' ><b>Closed Campgrounds</b></td></tr>

<tr ><th>Campground</th><th>Sites</th><th style='width:4em;'>Fee</th><th>Open SItes</th></tr>
	<?php foreach ($cgs as $cg) :
			if ($camps['cg_status'][$cg] == $status):
				$no_entries =false;?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td>$ <?= Defs::$campfees[$cg] ?> </td>

				<td><?= $camps['sites'][$cg] ?> </td>

				</tr>
			<?php  endif; ?>
		<?php endforeach;?>
		<?php if ($no_entries):?>
			<tr><td colspan='4' class='left'>None</td></tr>
		<?php endif; ?>
</table>
</div>
</div>

