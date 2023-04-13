<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


use DigitalMx\jotr\Calendar as Cal;
use DigitalMx\jotr\DisplayManager as DM;
$pcode ??= '';

?>

<!-- ############################## -->
<div id="page1" class='page'>
<?=$title_bar?>

<?php if ($admin['pithy']):

?>

	<p class='center'><i><?= $admin['pithy'] ?></i></p>
<?php endif; ?>

<?php
//Utilities::echor($data,'data');
	$this->insert('light',['light'=>$light]);
?>

<?php $this->insert('conditions')?>

<?php $this->insert('alerts')?>
<br />
<!--
</div> <!~~ end page~~>
<div id="page2" class='page' >
 -->


<?php $this->insert('notices') ?>


<?php $this->insert('advice')?>



</div>
<!-- end page-->
<div id="page3" class='page' >
<?=$title_bar?>

<?php
	$wspec = array('wslocs'=>['jr','cw','br'],'wsdays'=>3);
	$this->insert('weather',$wspec);

?>




</div>
<!-- end page-->
<div id="page4" class='page' ">
<?=$title_bar?>
<?php
$tcalendar['events'] = Calendar::filter_events($calendar['events'],3);
$this->insert('calendar',['calendar'=>$tcalendar])
?>

</div>
<!-- end page-->
<div id="page5" class='page' >
<?=$title_bar?>

<?php $this->insert('campground',['camps'=>$camps]); ?>
<?php $this->insert('qr_camps'); ?>

</div> <!-- end page-->
<div id="page6"  class='last-page' >
<!-- class !=  page prevents page break after -->
<?=$title_bar?>
<?php $this->insert('fees'); ?>
<?php $this->insert('qr_fees'); ?>


<?php $this->insert('sig'); ?>


</div> <!-- end page-->


