<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php if(empty($light)): echo "<p>No Data</p>"; else:
//u\echor($light,'in tpl', NOSTOP);
$lightd = $light['light'];
$uv = $light['uv'];
$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0];

$gday = $wgov['jr'][1];
$wday = $wapi['forecast']['jr'][0];
?>


<table style='width:100%;font-size:1.2rem;' >
<tr class='no-bottom' >
<td class='width50 center;'>
	<h3><u>Today</u></h3>
	<img src="<?=$wgov['jr'][1][0]['icon']?>" >


</td><td class='width50 center;'>

	<h3><u>Tonight</u></h3>
	<img src="/images/moon/<?= $lightd['moonpic'] ?>" style='width:76px' ><br />
	<?=$lightd['moonphase'] ?>

</td>
</tr>
<tr>
<td class='center'>
	<p><b><?= $gday[0]['shortForecast'] ?></b</p>
	<p  style='font-size:1rem;'>
		<?=$gday[0]['highlow']?><br />
		Sunrise: <?= $lightd['sunrise'] ?> Sunset: <?= $lightd['sunset'] ?>
	</p>
</td><td>
	<p ><b><?= $gday[1]['shortForecast'] ?></b></p>
	<p  style='font-size:1rem;'>
	<?=$gday[1]['highlow']?><br />
	Moonrise: <?= $lightd['moonrise'] ?>  Moonset: <?= $lightd['moonset'] ?><br />
	</p>
</td></tr>
</table>
<?php endif; ?>


</table>
