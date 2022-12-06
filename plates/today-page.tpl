<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

$pcode ??= '';

switch ($pcode) {
	case 'snap':
		$divvis='none';
		break;

	default:
		$divvis='block';
}

?>

<div >
<h2><?=$data['target'] ?> </h2>
<p class='pithy'><?=$data['pithy'] ?? '' ?></p>
</div>

<!-- ############################## -->
<div id="page1" style="display: block;">

<?php
	//$this->insert('light_condensed',['light' => $data['light']]);

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
	Sunrise <?=$lightd['sunrise']?>&nbsp;&nbsp;&nbsp;
	Sunset <?=$lightd['sunset']?>  <br /><br />
	<b>UV Exposure:</b> <?= $uv['uv'] ?>
	<span style = 'background-color:<?=$uv['uvcolor']?>;'><?=$uv['uvscale']?></span> <br />
</td>
<td  class = 'border  center bg-black white' >

<p><b>Tonight</b></p>
	Moonrise <?=$lightd['moonrise']?>&nbsp;&nbsp;&nbsp;
	Moonset <?=$lightd['moonset']?>


	<div style=' align-items:center;width:100%;margin-top:1em;'>

	<span><?=$lightd['moonphase']?></span>
	<img src= "/images/moon/<?=$lightd['moonpic'] ?>" style='vertical-align:middle;' /></div>
</td></tr>
</table>

<hr style="border:10px solid <?=$fire['color'] ?>;">
	 	<p style = 'width:100%;'>
	 	<b>Current Fire Level:</b>

	 	<?=$fire['level']?>
	 	</p>

			<div class='left'>
				<?=Defs::$firewarn[$fire['level']]?>
			</div>

<hr style="border:10px solid <?=$air['br']['aqi_color'] ?>;">

As of <?php echo date('M d h:i a',$air['br']['dt']) ?>
<table >
<tr><th>Location</th><th>Air Quality</th><th>Particulates (PM10)</th><th>Ozone</th></tr>
<?php //foreach ($air as $loc => $dat) :
	$dat = $air['br'];
?>

	<?=$dat['aqi']?>

		<?=$dat['aqi_scale'] ?></span>

	<td><?=$dat['pm10']?></td>
	<td><?=$dat['o3']?></td>




</div> <!-- end page-->
<div id="page2" style="display: <?=$divvis?>;">
<?php
	$this->insert('notices',['notices' => $data['notices']]);
?>
<?php
	$this->insert('advice',['advice' => $data['advice']]);
?>

</div> <!-- end page-->
<div id="page3" style="display: <?=$divvis?>;">

<?php
  $this->insert('calendar',['calendar' => $data['calendar']])
?>

</div> <!-- end page-->
<div id="page4" style="display: <?=$divvis?>;">


<?php $this->insert('weather_brief',['weather' => $data['weather'] ]); ?>




</div> <!-- end page-->
<div id="page5" style="display: <?=$divvis?>;">


<?php $this->insert('campground',['camps' => $data['camps'] ]); ?>

<div style='clear:left;'></div>

<hr>
<p id='bottom' class='right'><?=$data['version'] ?>
<br>build <?php echo date('dHi'); ?></p>

</div> <!-- end page-->

<?php $this->insert('scroll_script'); ?>


