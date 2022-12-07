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


<!-- ############################## -->
<div id="page1" style="display: block;">
<?php
$lightd = $data['light']['light'];;
$uv = $data['light']['uv'];
?>
	<table class='in2'>
		<colgroup> <col style='width:50%;' class='center border'> <col style='width:50%;' class='center border'> </colgroup>
		<tr>
			<td style='font-weight:bold;'>
			<p>
				<u>Today</u>
			</p>
			Sunrise:
<?=$lightd['sunrise']?>
			&nbsp;&nbsp;&nbsp; Sunset:
<?=$lightd['sunset']?>
			<br />
			<br />
			<b>UV Exposure:</b>
<?= $uv['uv'] ?>
			<span style='background-color:<?=$uv[' uvcolor']?>;'>
<?=$uv['uvscale']?>
			</span> <br />
			</td>
			<td style='font-weight:bold;' class=' bg-black white'>
			<p>
				<u>Tonight</u>
			</p>
			Moonrise:
<?=$lightd['moonrise']?>
			&nbsp;&nbsp;&nbsp; Moonset:
<?=$lightd['moonset']?>
			<div style=' align-items:center;width:100%;margin-top:1em;'>
				<span><?=$lightd['moonphase']?>
				</span> <img src="/images/moon/<?=$lightd['moonpic'] ?>" style='vertical-align:middle;' />
			</div>
			</td>
		</tr>
	</table>

<?php $this->insert('alerts',['notices' => $data['notices']]); ?>
<div class='clear'></div>
<h4>Park Conditions</h4>
	<div class='in2'>

	<div class='float '>
		<b>Fire Danger:</b>
<?=$data['fire']['level']?>
	</div>
	<div class='float '>
		<b>Air Quality: </b>
<?=$data['air']['br']['aqi']?>
 - <?=$data['air']['br']['aqi_scale'] ?><br />
<small>At Black Rock <?php echo date ('m/d g:i a',$data['air']['br']['observed_dt']);?></small>
	</div>
<div class='float'>
<?php // u\echor($data['current'],'current',STOP); ?>
	<b>Current Temp</b> :
	<?php
		echo
		$data['current']['temp_f'] ." &deg;F"
		. "<br /><small> Jumbo Rocks "
		. date('m/d g:i a',$data['current']['last_updated_epoch'])
		." </small>"
		. NL;
	?>
</div>
	<div class='clear'></div>
</div>
<!-- end page-->
	</div>
	<div id="page2" style="display: <?=$divvis?>;">

<?php $this->insert('announcements',['notices'=> $data['notices']]); ?>
		<div class='clear'>
		</div>
<?php	$this->insert('advice',['advice' => $data['advice']]);?>

<!-- end page-->
	</div>
	<div id="page3" style="display: <?=$divvis?>;">

<?php
  $this->insert('calendar',['calendar' => $data['calendar']])
?>

<!-- end page-->
	</div>
	<div id="page4" style="display: <?=$divvis?>;">

<?php $this->insert('weather',['weather' => $data['weather'] ]); ?>

<!-- end page-->
	</div>
	<div id="page5" style="display: <?=$divvis?>;">
<?php $this->insert('campground',['camps' => $data['camps'] ]); ?>
		<div style='clear:left;'>
		</div>

<!-- end page-->
	</div>
	<div id="page6" style="display: <?=$divvis?>;">
<?php $this->insert('fees'); ?>


<!-- end page-->
	</div>

<?php $this->insert('end'); ?>


<?php $this->insert('scroll_script'); ?>

<?php #echo '<hr>';u\echor($data,'data'); ?>
