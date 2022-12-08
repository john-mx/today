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
<table  class='in2 alt-gray border-bottom'>
<tr><th>Campground</th><th>Status</th><th>Sites</th>
<th>Features</th><th>Open Sites<br />as of <?= $asof ?></th><th>Note</th></tr>
<?php foreach (['ic','jr','sp','hv','be','wt','ry','br','cw'] as $cg) : ?>
	<tr class='border-bottom'>
		<td class='left'>  <?=Defs::$sitenames [$cg] ?>  </td>
	 <td> <?= $camps['cg_status'][$cg] ?> </td>
	<td> <?= Defs::$campsites[$cg] ?> </td>
		<td> <?= Defs::$campfeatures [$cg] ?> </td>
		<td><?= $camps['cg_open'][$cg] ?> </td>
	<td> <?= $camps['cg_notes'][$cg] ?>  </td>
	</tr>
	<?php endforeach;?>

</table>
<?php endif; ?>

<div>
<ul style='font-size:1rem;'>
<li>"First" means First Come; First Served.  Find an open campsite and claim it.  Pay a ranger at the campground or at the entrance station.
<li>Camp features:
	W: Water;
	D: RV Dump Site;
	G: Group sites;
	H: Horses

<li>Reservations are made ONLY using the recreation.gov web site or call at 1-877-444-6777. They cannot be made by park rangers.
</ul>

</div>
