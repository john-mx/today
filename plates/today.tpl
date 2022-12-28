<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;
use DigitalMx\jotr\Calendar as Cal;

$pcode ??= '';

switch ($pcode) {
	case 'snap':
		$divvis='none';
		break;

	default:
		$divvis='block';
}

$Cal = new Cal();
?>

<!-- ############################## -->
<div id="page1" style="display: block;">

<?php
//u\echor($data,'data');
	try {$this->insert('light');}
	catch (exception $e) {
		$einfo = ['src' => 'light','info'=>$e];
		$this->insert('error',$einfo);
	}
?>

<?php $this->insert('notices') ?>

<?php	$this->insert('conditions')?>

<?php $this->insert('advice')?>


</div> <!-- end page-->
<div id="page2" class='break' style="display: <?=$divvis?>;">

<?php $this->insert('weather') ?>

</div>
<!-- end page-->
<div id="page8" class='break' style="display: <?=$divvis?>;">



<?php
$tcalendar = $Cal->filter_calendar($calendar,2);
$this->insert('calendar',['calendar'=>$tcalendar])
?>

</div>
<!-- end page-->
<div id="page3" class='break' style="display: <?=$divvis?>;">


<?php $this->insert('campground'); ?>

</div> <!-- end page-->
<div id="page5"  class='break' style="display: <?=$divvis?>;">

<?php $this->insert('fees'); ?>


<?php $this->insert('end'); ?>


</div> <!-- end page-->


