<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<?php if(empty($light)): echo "<p>No Data</p>"; else:
//u\echor($light,'in tpl', NOSTOP);
$lightd = $light['light'];
$uv = $light['uv'];
?>
<table class='in2'>
<colgroup>
	<col style='width:50%'>
	<col style='width:50%'>
</colgroup>

<tr><td style='width:45%;' class = ' center' >

<p><b>Today</b></p>
	<b>Sunrise <?=$lightd['sunrise']?>&nbsp;&nbsp;&nbsp;
	Sunset <?=$lightd['sunset']?>  <br /><br />
	</b>
	<b>UV Exposure:</b> <?= $uv['uv'] ?>
	<span style = 'background-color:<?=$uv['uvcolor']?>;'><?=$uv['uvscale']?></span> <br />

	<?=$uv['uvwarn']?>
</td>
<td  class = 'border  center bg-black white' >

<p><b>Tonight</b></p>
<b>	Moonrise <?=$lightd['moonrise']?>&nbsp;&nbsp;&nbsp;
	Moonset <?=$lightd['moonset']?>


	<div style=' align-items:center;width:100%;margin-top:1em;'>

	<span><?=$lightd['moonphase']?></span>
	<img src= "/images/moon/<?=$lightd['moonpic'] ?>" style='vertical-align:middle;' /></div>
</td></tr>
</table>

<?php endif; ?>
