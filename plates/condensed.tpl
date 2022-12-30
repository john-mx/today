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

$Cal = new DigitalMx\jotr\Calendar();
	$calendar = $Cal->filter_calendar($calendar,2);
?>

<div class='content center'>
<!-- ############################## -->
<div id="page-today" style="display: block">

<?php if ($admin['pithy']): ?>
	<p><i><?=$admin['pithy']?></i></p>
<?php endif; ?>


<!-- start light -->
<?php $this->insert('light',[$light]); ?>

<!-- start conditions -->
<?php	$this->insert('conditions');?>

</div><!-- end page-->
<div id="page-notices" style="display: <?=$divvis?>;">
<!-- start notices -->
<?php $this->insert('notices',['notices' => $admin['notices']]); ?>

<?php $this->insert('advice',['advice' => $admin['advice']]); ?>

</div><!-- end page-->
<div id="page-weather" style="display: <?=$divvis?>;">

<?php $wgov_update = $wgov['update'] ?? 0;
	if (time() - $wgov_update > 60*60*12): #12 hours
		$this->insert('weather-wapi',['wapi'=>$wapi]);
	else:
		$this->insert('weather-jr',['weather'=>$wgov]);
	endif;
	?>

</div><!-- end page-->
<div id="page-events" style="display: <?=$divvis?>;">

<?php
		$this->insert('calendar',['calendar' => $calendar]) ?>

</div><!-- end page-->
<div id="page-camps" style="display: <?=$divvis?>;">
<?php $this->insert('campground-wide',$camps); ?>

</div><!-- end page-->
<div id="page-fees" style="display: <?=$divvis?>;">

<?php $this->insert('feesC'); ?>

</div>
<!-- end page-->
<!--
<div id="page-feesB" style="display: <?=$divvis?>;">

<?php $this->insert('feesB'); ?>

<?php $this->insert('end'); ?>

</div>
 -->
<!-- end page-->


</div> <!-- end content -->


