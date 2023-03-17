<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;



if (!$cga ){
	echo "No Data Available (cga)";
	return;
}

?>
<style>@media print {
	@page {size: landscape;}
	}
	table {float:left;
		page-break-inside : avoid;
		margin-bottom:6px;}
	table tr td {
		font-size:9pt;
		padding:2px;
	}
</style>

<h3>Campground Attributes</h3>
<?php
	foreach ($cga as $loc=>$sites) :
	?>

	<h4><?php echo LS::getLocName($loc); ?></h4>

	<?php
		$lines=0;
		foreach (array_keys($sites) as $site):
			if ($lines%25 == 0) :
				if ($lines != 0 ) : ?>
					</table>
				<?php endif; ?>

				<table>
	<tr><th>Site</th><th>Max Veh</th><th>Max Length</th><th>Max People</th></tr>
	<?php
		endif;
	 $attr = $sites[$site];
	 ++$lines;
	 ?>

<tr><td><?=$site?></td>
	<td><?=$attr['Max Num of Vehicles'] ?></td>
	<td><?=$attr['Max Vehicle Length'] ?></td>
	<td><?=$attr['Max Num of People'] ?></td>
	</td></tr>
	<?php endforeach ; ?>
</table>

<?php endforeach; ?>