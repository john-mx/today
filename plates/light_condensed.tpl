<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php if(empty($light)): echo "<p>No Data</p>"; else:
//u\echor($light,'in tpl', NOSTOP);
//u\echor($wgov['jr'],'wgov jr');

$lightd = $light['light'];
$uv = $light['uv'];
$gday = $wgov['jr'][1]; #first day
if (!isset($gday[1])){ #no day, only night;
	$gday[1] = $gday[0];
	$gday[0] = [];
}
$wday = $wapi['forecast']['jr'][0];


?>


<table style='width:100%;font-size:1.2rem;' >
<tr class='no-bottom' >
<td class='width50 center;'>
	<h3><u>Today</u></h3>
	<?php if ($gday[0] ): ?>
		<img src="<?=$wgov['jr'][1][0]['icon']?>" >}
	<?php endif;?>
</td><td class='width50 center;'>
	<h3><u>Tonight</u></h3>
	<img src="/images/moon/<?= $lightd['moonpic'] ?>" style='width:76px' ><br />
	<?=$lightd['moonphase'] ?>

</td>
</tr>
<tr class='no-top'>
<td class='center'>
<?php if ($gday[0] ): ?>
	<p><b><?= $gday[0]['shortForecast'] ?? '' ?></b</p>
	<p  style='font-size:1rem;'>
		<?=$gday[0]['highlow']?><br />
		Sunrise: <?= $lightd['sunrise'] ?> Sunset: <?= $lightd['sunset'] ?>
	</p>
	<?php else: ?>
		N/A
	<?php endif; ?>
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
