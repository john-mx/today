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
//U::echor($cga,'cga',STOP);
?>

<?php //foreach ($cga as $loc=>$sites) :
	$sites = $cga;
?>
<div class='landscape last-page'>
<b>Campground Attributes for <?php echo LS::getLocName($loc) . ' ' . TODAY; ?></b><br />
Max length = vehicle length unless (size). Prints one page in landscape. Data from recreation.gov. <br />
Permitted: C=Camper/van/pickup; L=Trailer/5th-wh; R=RV; V=vehicle;  Ground: T2,T4,T6=tent:small,med,large; U=pop-up;<br />

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
	<td><?=$attr['Max Vehicle Length']?? 0 ?></td>
	<td><?=$attr['Max Num of People'] ?></td>
	<td><?=$attr['permitted'] ?></td>
</tr>
	<?php endforeach ; ?>
</table>
</div>

