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

<!-- ############################## -->
<div id="page1" style="display: block;">

<?php
	$this->insert('light_condensed',['light' => $data['light']]);
?>
<?php
	$this->insert('notices',['notices' => $data['notices']]);
?>

<?php
	$conditions = array(
		'fire'=>$data['fire'],
		'air'=>$data['air'],
		'current' =>  $data['current'],
		);
	$this->insert('conditions',$conditions);
?>

<div class='clear'></div>
<?php
	$this->insert('advice',['advice' => $data['advice']]);
?>
</div> <!-- end page-->
<div id="page2" style="display: <?=$divvis?>;">


<?php $this->insert('weather_brief',['weather' => $data['weather'] ]); ?>

</div> <!-- end page-->

<div id="page3" class='break' style="display: <?=$divvis?>;">
<?php $this->insert('calendar',['calendar' => $data['calendar']])?>


</div> <!-- end page-->
<div id="page4"  style="display: <?=$divvis?>;">





<!-- ############################## -->




<?php $this->insert('campground',['camps' => $data['camps'] ]); ?>

</div> <!-- end page-->
<div id="page5"  class='break' style="display: <?=$divvis?>;">

<?php $this->insert('fees'); ?>


<?php $this->insert('end'); ?>


</div> <!-- end page-->


