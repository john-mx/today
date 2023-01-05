<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

 $qs ??= '';

switch ($qs) {
	case 'snap':
		$divvis='none';
		break;
	case 'snap2':
		$divvis='none';
		break;

	default:
		$divvis='block';
}

if (!empty($calendar)){
$Cal = new DigitalMx\jotr\Calendar();
	$calendar = $Cal->filter_calendar($calendar,2);
}
?>

<div class='content center'>
<!-- ############################## -->
<div id="page-today" style="display: block;transform-origin:top center;" >
<!-- set transform on this page to scale to fit available display height -->

<?php if ($admin['pithy']): ?>
	<p class='center'><i><?=$admin['pithy']?></i></p>
<?php endif; ?>


<!-- start light -->
<?php $this->insert('light',[$light]); ?>

<!-- start conditions -->
<?php	$this->insert('conditions');?>



</div><!-- end page-->

<div id="page-notices" style="display: <?=$divvis?>;">
<!-- start notices -->
<?php $this->insert('alerts',['alerts' => $admin['notices']['alerts'] ])?>

<?php $this->insert('notices',['notices' => $admin['notices']]); ?>

<?php $this->insert('advice',['advice' => $admin['advice']]); ?>

</div><!-- end page-->
<div id="page-weather" style="display: <?=$divvis?>; " >


<?php

	//u\echor($wgov,'wgov',STOP);
	$wspec=array('wslocs'=>['jr','cw'],'wsdays'=>3);
	$this->insert('weather',$wspec);

	?>

</div><!-- end page-->

<?php if (!empty($calendar)) : ?>
<div id="page-events" style="display: <?=$divvis?>;">

<?php
		$this->insert('calendar',['calendar' => $calendar]) ?>

</div><!-- end page-->
<?php endif; ?>

<div id="page-camps" style="display: <?=$divvis?>;">
<?php $this->insert('campground-wide',$camps); ?>

</div><!-- end page-->
<div id="page-fees" style="display: <?=$divvis?>;">

<?php $this->insert('fees'); ?>

</div>


</div> <!-- end content -->


