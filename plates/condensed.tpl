<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;



 $qs ??= '';

switch ($qs) {
	case 'snap':
		$divvis='none';
		break;

	default:
		$divvis='block';
}

if (!empty($calendar)){
//$Cal = new Calendar();
	$caldays = []; // count of events by day
	$calendar = Calendar::filter_calendar($calendar,2);
//	Utilities::echor($calendar,'calendar filtered', STOP);

	//Utilities::echor($caldays,'caldays',STOP);
}
?>
<!--
setTimeout(() => {
  document.location.reload();
}, 3000);
 -->

<div id = 'content' class='content center'>
<!-- ############################## -->
<div id='loadholder' style="display:block">
<br /><br /><br />(Refreshing...)
</div>
<div id="page-today" class='page' style='display:<?=$divvis?>;' >
<!-- set transform on this page to scale to fit available display height -->
<!--
<?php if ($local_site): ?>
	<h3>Welcome to the <?=Defs::$sitenames[$local_site]?></h3>
	<?php endif; ?>
 -->
<?php if (0 && $admin['pithy']): ?>
	<p class='center'><i><?=$admin['pithy']?></i></p>
<?php endif; ?>


<!-- start light -->
<?php $this->insert('light',[$light]); ?>

<?php $this->insert('alerts')?>


<?php $this->insert('end'); ?>

<div id='page-today-scale' class='scaler'></div>
</div><!-- end page-->

<div id="page-notices" style="display: <?=$divvis?>;"  class="page">
<!-- start notices -->


<?php $this->insert('notices',['notices' => $admin['notices']]); ?>

<?php $this->insert('advice',['advice' => $admin['advice']]); ?>


<div id='page-notices-scale'  class='scaler'></div>
</div><!-- end page-->
<div id="page-weather" style="display: <?=$divvis?>; " class="page" >


<!-- start conditions -->
<?php	$this->insert('conditions');?>
<?php
	//Utilities::echor($wgov,'wgov',STOP);
	$wspec=array('wslocs'=>['jr'],'wsdays'=>3);
	$this->insert('weather-tv',$wspec);
//U::echor ($wgov,'wgov',STOP);
	?>
<div id='page-weather-scale'  class='scaler'></div>
</div><!-- end page-->

<?php if (!empty($calendar)) : ?>
<div id="page-events" style="display: <?=$divvis?>;" class="page">

<?php  //use filtered calewndar from top o page.
		$this->insert('calendar-tv',['calendar' => $calendar]) ?>

<div id='page-events-scale'  class='scaler'></div>
</div><!-- end page-->
<?php endif; ?>

<div id="page-camps" style="display: <?=$divvis?>;" class="page">
 <?php $this->insert('campground-tv',$camps); ?>
<div id='page-camps-scale'  class='scaler'></div>
</div><!-- end page-->
<div id="page-fees" style="display: <?=$divvis?>;" class="page">

<?php $this->insert('fees'); ?>
<div id='page-fees-scale'  class='scaler'></div>
</div>


</div> <!-- end content -->


