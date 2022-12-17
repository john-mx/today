<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx as u;

$qs = $data['qs'] ?? '';

switch ($qs) {
	case 'snap':
		$divvis='none';
		break;

	default:
		$divvis='block';
}

?>

<div class='content center'>
<!-- ############################## -->
<div id="page1" style="display: block;">
<?php if ($data['pithy']): ?>
	<p><i><?=$data['pithy']?></i></p>
<?php endif; ?>



<?php $this->insert('light',['data'=>$data]); ?>
<?php $this->insert('notices',['notices' => $data['notices']]); ?>

<?php	$this->insert('conditions',$data);?>

<!-- end page-->
</div>

<!--
<div id="page2" style="display: <?=$divvis?>;">

	<?php $this->insert('advice',['advice' => $data['advice']]); ?>

<!~~ end page~~>
</div>
 -->

<div id="page4" style="display: <?=$divvis?>;">

<?php $this->insert('weather-jr',['weather' => $data['wgov'] ]); ?>
</div>
<!-- end page-->
<?php if ($data['calendar']): ?>
<div id="page3" style="display: <?=$divvis?>;">

<?php

		$this->insert('calendar',['calendar' => $data['calendar']]) ?>
</div>
<!-- end page-->
<?php endif; ?>

<div id="page5" style="display: <?=$divvis?>;">

<?php $this->insert('campground-wide',['camps' => $data['camps'] ]); ?>
</div>

<!-- end page-->
<div id="page6" style="display: <?=$divvis?>;">

<?php $this->insert('feesA'); ?>
</div>
<!-- end page-->
<div id="page7" style="display: <?=$divvis?>;">
<?php $this->insert('feesB'); ?>

<?php $this->insert('end'); ?>
</div>
<!-- end page-->


</div> <!-- end content -->


