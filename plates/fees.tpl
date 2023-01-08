<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
$fees = Defs::getFees();

?>
<h4>Entrance Fees</h4>
<div class='inleft2 red'>
<b>Help Keep The Line Moving –
Purchase Your Pass Before You Get To The Entrance</b>
</div>
<?php //u\echor($fees,'fees');
	$lastclass = '';
?>

<table  class='inleft2'>
<tr style='col-border;' ><th colspan='4' class='no-bottom'></th><th colspan='3' style='border-bottom:1px solid black;'>Where to Buy</th></tr>
<tr  style='background-color:white;' class='no-borders col-border ' ><th>Pass</th><th>For</th><th>Valid For</th><th>Price</th><th>Rec.gov</th><th>Entrance Station</th><th>Visitor Center</th></tr>

<?php foreach ($fees as $feeclass => $feedata):
	if ($feeclass !== $lastclass):
	?>

	<tr><td colspan=7  style='font-weight:bold;text-align:left;' class='bg-orange'><b><?=$feeclass?></b></td></tr>
	<?php $lastclass = $feeclass; endif;?>

		<?php foreach ($feedata as $feeitem): ?>
		<tr>
		<td style='font-weight:bold;text-align:left;'>
			<?=$feeitem['name']?></td>
		<td><?=$feeitem['for']?></td>
		<td><?=$feeitem['valid']?></td>
		<td><?=$feeitem['price']?></td>
		<?php foreach(['rec','ent','vc'] as $here):
			echo "<td>";
			if (in_array($here,$feeitem['avail']) ): echo "✔︎"; endif;
			echo "</td>";

			endforeach;
		?>
	</tr>
	<?php endforeach; ?>


<?php endforeach; ?>

</table>
<div class='inleft2'><b>On-line passes available at recreation.gov. Entrance stations take credit card only — no cash.</b><br />
* Documentation required for special passes.  4th grade student passes through everykidoutdoors.gov.
</div>
<div class='center'>
<img src='/images/Digital-Pass-Icon.png' style='height:120px;margin:1em;;' alt='https://www.recreation.gov/sitepass/74286'>
</div>
