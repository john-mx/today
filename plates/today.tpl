<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
?>

<div >
<h2><?=$data['target'] ?> </h2>
<p class='pithy'><?=$data['pithy'] ?? '' ?></p>
</div>

<!-- ############################## -->
<!--
<div id='page1'>
 -->


<?php
	$this->insert('light',['light' => $data['light']]);
?>
<?php
	$this->insert('notices',['notices' => $data['notices']]);
?>
<?php
  $this->insert('calendar',['calendar' => $data['calendar']])
?>

<!--
<div id='page2' style="page-break-after: always;"><br /></div>
 -->


<div class='in2' style='width:45%; float:left;'>
<?php $this->insert('fire',['fire' => $data['fire']]);
?>
</div>

<div class='in2 float' style='width:45%;'>
<?php $this->insert('airnow',['air'=> $data['air'] ?? [] ]); ?>
</div>

<div class='clear'></div>
<!--
<div id='page4'><br /></div>
 -->

<!-- ############################## -->

<!--
</div><br />
 -->
<!--
<div id='page2'>
 -->

<?php $this->insert('weather_brief',['weather' => $data['weather'] ]); ?>

<?php $this->insert('campground',['camps' => $data['camps'] ]); ?>

<div style='clear:left;'></div>

<hr>
<p id='bottom' class='right'><?=$data['version'] ?>
<br>build <?php echo date('dHi'); ?></p>
