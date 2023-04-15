<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;



if (!$cga ){
	echo "No Data Available (cga)";
	return;
}
$last_site1 = '';
?>
<style>
@page {
   	size: 11in 8.5in;
      margin: 0.3in;
      padding 0;


   }
	table {float:left;
		page-break-inside : avoid;
		margin-bottom:6px;}
	table tr td , table tr th{
		font-size:9pt;
		padding:2px;
		width:3rem;
	}
</style>


<?php foreach ($cga as $loc=>$sites) : ?>
<h4>Campground Attributes for <?php echo LS::getLocName($loc) . ' ' . TODAY; ?></h4>
Permitted: C=camper; L=trailer; Tx=tent:small,med,large; R=RV; U=pop-up; (size)<br />


	<?php
		$lines=0;
		foreach (array_keys($sites) as $site):
			$site1 = substr(trim($site),0,1);
			if ($site1 == 'G' && $last_site1 !== 'G'):
				echo "<tr><td colspan=4>&nbsp;</td></tr>\n";
				$last_site1 = 'G';
			endif;
			if ($lines%30 == 0) :
				if ($lines != 0 ) : ?>
					</table>
				<?php endif; ?>
				<table>
	<tr><th>Site</th><th>Veh</th><th>Len</th><th>Ppl</th><th>Permitted</th></tr>
	<?php
		endif;
	 $attr = $sites[$site];
	 ++$lines;

	 ?>

<tr><td><?=$site?></td>
	<td><?=$attr['Max Num of Vehicles'] ?></td>
	<td><?=$attr['Max Vehicle Length']?? 'n/a' ?></td>
	<td><?=$attr['Max Num of People'] ?></td>
	<td><?=$attr['permitted'] ?></td>
</tr>
	<?php endforeach ; ?>
</table>

<?php endforeach; ?>