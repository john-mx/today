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
//u\echor($data,'data');
	$this->insert('light',['data' =>$data]);
?>
<?php
	$this->insert('notices',['notices' => $data['notices']]);
?>

<?php

	$this->insert('conditions',$data);
?>

<?php $this->insert('advice',['advice' => $data['advice']]); ?>


</div> <!-- end page-->
<div id="page2" class='break' style="display: <?=$divvis?>;">

<?php $this->insert('weather',['weather' => $data['wgov'] ]); ?>

<?php $this->insert('calendar',['calendar' => $data['calendar']])?>

<!-- end page-->
</div>
<div id="page3" class='break' style="display: <?=$divvis?>;">


<?php $this->insert('campground',['camps' => $data['camps'] ]); ?>

</div> <!-- end page-->
<div id="page5"  class='break' style="display: <?=$divvis?>;">

<?php $this->insert('fees'); ?>


<?php $this->insert('end'); ?>


</div> <!-- end page-->


