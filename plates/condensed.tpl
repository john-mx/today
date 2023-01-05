<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

 $qs ??= '';

switch ($qs) {
	case 'snap':
		$divvis='none';
		break;

	default:
		$divvis='block';
}

if (!empty($calendar)){
$Cal = new DigitalMx\jotr\Calendar();
	$caldays = []; // count of events by day
	$calendar = $Cal->filter_calendar($calendar,3);
//	u\echor($calendar,'calendar filtered', STOP);

	//u\echor($caldays,'caldays',STOP);
}
?>

<div class='content center'>
<!-- ############################## -->
<div id="page-today" class='page' style="display: block;" >
<!-- set transform on this page to scale to fit available display height -->

<?php if ($admin['pithy']): ?>
	<p class='center'><i><?=$admin['pithy']?></i></p>
<?php endif; ?>


<!-- start light -->
<?php $this->insert('light',[$light]); ?>

<!-- start conditions -->
<?php	$this->insert('conditions');?>



</div><!-- end page-->

<div id="page-notices" style="display: <?=$divvis?>;"  class="page">
<!-- start notices -->
<?php $this->insert('alerts',['alerts' => $admin['notices']['alerts'] ])?>

<?php $this->insert('notices',['notices' => $admin['notices']]); ?>

<?php $this->insert('advice',['advice' => $admin['advice']]); ?>


</div><!-- end page-->
<div id="page-weather" style="display: <?=$divvis?>; " class="page" >


<?php

	//u\echor($wgov,'wgov',STOP);
	$wspec=array('wslocs'=>['jr','cw'],'wsdays'=>3);
	$this->insert('weather-tv',$wspec);

	?>

</div><!-- end page-->

<?php if (!empty($calendar)) : ?>
<div id="page-events" style="display: <?=$divvis?>;" class="page">

<?php  //use filtered calewndar from top o page.
		$this->insert('calendar-tv',['calendar' => $calendar]) ?>

</div><!-- end page-->
<?php endif; ?>

<div id="page-camps" style="display: <?=$divvis?>;" class="page">
<?php $this->insert('campground-tv',$camps); ?>

</div><!-- end page-->
<div id="page-fees" style="display: <?=$divvis?>;" class="page">

<?php $this->insert('fees'); ?>

</div>


</div> <!-- end content -->


