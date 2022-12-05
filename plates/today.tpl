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
	$this->insert('light_condensed',['light' => $data['light']]);
?>
<?php
	$this->insert('notices',['notices' => $data['notices']]);
?>
<?php
	$this->insert('advice',['advice' => $data['advice']]);
?>
</div> <!-- end page-->
<div id="page2" style="display: <?=$divvis?>;">

<?php
  $this->insert('calendar',['calendar' => $data['calendar']])
?>

</div> <!-- end page-->
<div id="page3" style="display: <?=$divvis?>;">

<?php $this->insert('weather_brief',['weather' => $data['weather'] ]); ?>

</div> <!-- end page-->
<div id="page4" style="display: <?=$divvis?>;">


<div class='in2' style='width:45%; float:left;'>
<?php $this->insert('fire',['fire' => $data['fire']]);
?>
</div>

<div class='in2 float' style='width:45%;'>
<?php $this->insert('airnow',['air'=> $data['air'] ?? [] ]); ?>
</div>

<div class='clear'></div>

</div> <!-- end page-->
<div id="page5" style="display: <?=$divvis?>;">


<!-- ############################## -->




<?php $this->insert('campground',['camps' => $data['camps'] ]); ?>

<div style='clear:left;'></div>

<hr>
<p id='bottom' class='right'><?=$data['version'] ?>
<br>build <?php echo date('dHi'); ?></p>

</div> <!-- end page-->

<?php $this->insert('scroll_script'); ?>


