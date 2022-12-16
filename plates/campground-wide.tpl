<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

$asof =  date('M d g:i a', $camps['asof']);
?>



<?php if(empty($camps)): echo "No Campground Data"; else: ?>
<h4>Campgrounds
<?php if (!empty($camps['cgfull'])) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?>
</h4>
<div class='width100'>
<p>Open Sites as of <?= $asof ?></p>
<?php
$cgs = array_keys(Defs::$campsites);
sort ($cgs);
?>

<table class='alt-gray border inlineblock width45'>
<tr ><th>Campground</th><th>Sites</th><th style='width:4em;'>Fee</th>
<th>Open Sites</th>
</tr>
	<?php foreach (['Reservation'] as $status):
		if ($status == 'Reservation') : ?>
		<tr class='bg-orange left'><td colspan='5' ><b>Reserved Campgrounds</b><br /> Make reservations at rec.gov <br />or call 1-877-444-6777. </td></tr>
		<?php elseif ($status=='First') : ?>
		<tr class='bg-orange left'><td colspan='5' ><b>First Come, First Served Campgrounds</b> <br />Find an empty site and claim it. Pay ranger or at entrance station.</td></tr>
		<?php elseif ($status=='Closed') : ?>
			<tr class='bg-orange left'><td colspan='5' ><b>Closed Campgrounds</b></td></tr>
		<?php endif;?>

		<?php
		$nada=true;
		foreach ($cgs as $cg) : ?>
			<?php if ($camps['cg_status'][$cg] == $status):
				$nada=false;?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td>$ <?= Defs::$campfees[$cg] ?> </td>

				<td><?= $camps['cg_open'][$cg] ?> </td>

				</tr>
			<?php  endif; ?>
		<?php endforeach;?>
		<?php if ($nada):?>
			<tr><td colspan='5' class='left'>None</td></tr>
		<?php endif; ?>
	<?php endforeach; ?>
	</table>

	<div style='width:2em;' class='inlineblock'> </div>


	<table  class='alt-gray border inlineblock  width45' style='vertical-align:top;' >
<tr ><th>Campground</th><th>Sites</th><th style='width:4em;'>Fee</th>
<th>Open Sites</th></tr>

	<?php foreach (['First','Closed'] as $status):
		if ($status == 'Reservation') : ?>
		<tr class='bg-orange left'><td colspan='5' ><b>Reserved Campgrounds</b> <br />Make reservations at rec.gov <br />or call 1-877-444-6777. </td></tr>
	`	<?php elseif ($status=='First') : ?>
		<tr class='bg-orange left'><td colspan='5' ><b>First Come, First Served Campgrounds</b> <br />Find an empty site and claim it. Pay ranger or at entrance station.</td></tr>
		<?php elseif ($status=='Closed') : ?>
			<tr class='bg-orange left'><td colspan='5' ><b>Closed Campgrounds</b></td></tr>
		<?php endif;?>

		<?php
		$nada=true;
		foreach ($cgs as $cg) : ?>
			<?php if ($camps['cg_status'][$cg] == $status):
				$nada=false;?>
				<tr class='border-bottom'>
				<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
				<td> <?= Defs::$campsites[$cg] ?> </td>
				<td>$ <?= Defs::$campfees[$cg] ?> </td>
				<td><?= $camps['cg_open'][$cg] ?> </td>

				</tr>
			<?php  endif; ?>
		<?php endforeach;?>
		<?php if ($nada):?>
			<tr><td colspan='5' class='left'>None</td></tr>
		<?php endif; ?>
	<?php endforeach;  ?>
	</table>
	</div>

	<div class='clear'></div>
</div>
<?php endif; ?>

