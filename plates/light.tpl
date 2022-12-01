<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php if(empty($light)): echo "<p>No Data</p>"; else:
//u\echor($light,'in tpl', NOSTOP);
$lightd = $light['light'];
$uv = $light['uv'];
?>

<table class = 'in2'>
<colgroup>
	<col style='width:50%;'>
	<col style='width:50%;'>

</colgroup>

<tr class='no-border'><td ><b>Today</b></td><td class='bg-black white'><b>Tonight</b></td></tr>
<tr class='no-border'>
	<td>Sunrise <?=$lightd['sunrise']?> Set <?=$lightd['sunset']?> </td>
<td class='bg-black white' >Moonrise <?=$lightd['moonrise']?> Set <?=$lightd['moonset']?></td>
</tr>

<tr class='no-border'>
	<td ><p style='width:100%'><b>UV Exposure:</b> <?= $uv['uv'] ?>
	<span style = 'background-color:<?=$uv['uvcolor']?>;'>   <?=$uv['uvscale']?></span></p>
	<p><?=$uv['uvwarn']?></p>

	</td>
	<td class='bg-black' ><p class='white'><?=$lightd['moonphase']?></p>
	<img src= "/images/moon/<?=$lightd['moonpic'] ?>" /></td>
</tr>



</table>
<?php endif; ?>
