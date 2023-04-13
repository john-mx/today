<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

$divvis = 'block';
if ($meta['rotation']['animation'] == 'snap'){
      $divvis='none';
}
else {
	$divvis='block';
}
$calendarf = []; //filtered calendar
$filterday = 1;
while (empty($calendarf)){
// normally, get events for today;
// if there are none, get tomorrow;s event instead.
// keep going until you have some events
	$calendarf = Calendar::filter_events($calendar['events'],$filterday);
	//U::echor($calendarf);
		++$filterday;
		if ($filterday > 7) break;
}
$calendar = $calendarf;

?>


<div id = 'content' class='content center'>
<!-- ############################## -->
<!--
<div id='loadholder' style="display:block">
<br /><br /><br />(Refreshing...)
</div>
 -->
<div id="page-today" class='page width100' style='display:<?=$divvis?>;' >


<!-- set transform on this page to scale to fit available display height -->
<!--

<?php if (0 && $admin['pithy']): ?>
	<p class='center'><i><?=$admin['pithy']?></i></p>
<?php endif; ?>


<!-- start light -->
<?php $this->insert('light',['light'=>$light]); ?>
<?php	$this->insert('conditions');?>



<div id='page-today-scale' class='scaler'></div>
</div><!-- end page-->

<div id="page-notices" style="display: <?=$divvis?>;"  class="page">
<!-- start notices -->

<?php $this->insert('alerts')?>

<?php $this->insert('notices',
	['notices' => array_merge($admin['notices']) ] ); ?>

<?php $this->insert('advice',[
	'animation'=>$meta['rotation']['animation']]); ?>

<div id='page-notices-scale'  class='scaler'></div>
</div><!-- end page-->
<div id="page-weather" style="display: <?=$divvis?>; " class="page" >


<!-- start conditions -->

<?php
	//Utilities::echor($wgov,'wgov',STOP);
	$wspec=array('wslocs'=>['jr','cw'],'wsdays'=>3);
	$this->insert('weather-tv',$wspec);
//U::echor ($wgov,'wgov',STOP);
	?>
<div id='page-weather-scale'  class='scaler'></div>
</div><!-- end page-->


<div id="page-events" style="display: <?=$divvis?>;" class="page">

<?php  //use filtered calewndar from top o page.
		$this->insert('calendar-tv',['calendar' => $calendar]) ?>

<div id='page-events-scale'  class='scaler'></div>
</div><!-- end page-->


<div id="page-camps" style="display: <?=$divvis?>;" class="page">

 <?php $this->insert('campground-tv',['camps'=>$camps]); ?>
<div id='page-camps-scale'  class='scaler'></div>
</div><!-- end page-->

<div id="page-fees" style="display: <?=$divvis?>;" class="last-page">

<?php $this->insert('fees'); ?>
<div id='page-fees-scale'  class='scaler'></div>
</div> <!-- end page -->

</div> <!-- end content -->

<?php $this->insert('sig'); ?>
