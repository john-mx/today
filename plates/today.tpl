<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


use DigitalMx\jotr\Calendar as Cal;
use DigitalMx\jotr\Today;
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
//Utilities::echor($data,'data');
	try {$this->insert('light');}
	catch (exception $e) {
		$einfo = ['src' => 'light','info'=>$e];
		$this->insert('error',$einfo);
	}
?>

<?php	$this->insert('conditions')?>

<?php $this->insert('alerts')?>
<br />
<!--
</div> <!~~ end page~~>
<div id="page2" class='break' style="display: <?=$divvis?>;">
 -->


<?php $this->insert('notices') ?>


<?php $this->insert('advice')?>



</div>
<!-- end page-->
<div id="page3" class='break' style="display: <?=$divvis?>;">


<?php
	$wspec = array('wslocs'=>['jr','cw','br','hq'],'wsdays'=>3);
	$this->insert('weather',$wspec);

?>




</div>
<!-- end page-->
<div id="page4" class='break' style="display: <?=$divvis?>;">

<?php
$tcalendar = $Cal->filter_calendar($calendar,3);
$this->insert('calendar',['calendar'=>$tcalendar])
?>

</div>
<!-- end page-->
<div id="page5" class='break' style="display: <?=$divvis?>;">


<?php $this->insert('campground'); ?>

</div> <!-- end page-->
<div id="page6"  class='break' style="display: <?=$divvis?>;">

<?php $this->insert('fees'); ?>


<?php $this->insert('end'); ?>


</div> <!-- end page-->


