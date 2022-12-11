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
	<table class='indent2'>
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
			<span style='background-color:<?=$uv['uvcolor']?>;'>
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
			<div class='bg-black' style=' align-items:center;margin-top:1em;'>
				<span><?=$lightd['moonphase']?>
				</span> <img src="/images/moon/<?=$lightd['moonpic'] ?>" style='vertical-align:middle;' />
			</div>
			</td>
		</tr>
	</table>

<?php $this->insert('notices',['notices' => $data['notices']]); ?>

<?php
	$conditions = array(
		'fire'=>$data['fire'],
		'air'=>$data['air'],
		'current' => $data['current'],
		);
	$this->insert('conditions',$conditions);
?>

<!-- end page-->
	</div>
	<div id="page2" style="display: <?=$divvis?>;">

	<?php $this->insert('advice',['advice' => $data['advice']]); ?>

<!-- end page-->
	</div>
	<div id="page4" style="display: <?=$divvis?>;">

<?php $this->insert('weather',['weather' => $data['weather'] ]); ?>

<!-- end page-->
	</div>
	<div id="page3" style="display: <?=$divvis?>;">

<?php
  $this->insert('calendar',['calendar' => $data['calendar']])
?>


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




<?php $this->insert('end'); ?>

<!-- end page-->
	</div>


<?php #echo '<hr>';u\echor($data,'data'); ?>
