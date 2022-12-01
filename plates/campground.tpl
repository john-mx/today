<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

$asof =  date('M d g:i a', $camps['asof']);
?>


<h3>Campgrounds</h3>



<?php if(empty($camps)): echo "No Data"; else: ?>
<p>Open sites as of <?= $asof ?>
<?php if (!empty($camps['cgfull'])) : ?>
	<span class='red'><b>ALL CAMPGROUNDS ARE FULL!</b></span>
<?php endif; ?> </p>
<table  class='in2 alt-gray border-bottom'>
<tr><th>Campground</th><th>Status</th><th>Sites</th>
<th>Features</th><th>Open</th><th>Note</th></tr>
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

<div  style='float:left;width:30%;margin-left:2em;'><br />"First" means First Come; First Served.  Find an open campsite and claim it.  Pay a ranger at the campground or at the entrance station.</div>
.
<div  style='float:left;width:30%;'>
<p>Camp features:<br>
	W: Water at Campground<br>
	D: Dump Site for RVs<br>
	G: Group sites available for large groups.<br>
	H: Horse sites
</p>
</div>
.
<div style='float:left;width:30%'>
<p>Reservations are made ONLY using the recreation.gov web site or call at 1-877-444-6777. They cannot be made by park rangers. </p>

</div>

<div style='clear:left;'></div>

</div>
