<?php
	//$trial = "<p>Software in Development</p>";
	$trial ??= '';
	$title = 'Today in Joshua Tree National Park';
	if(empty($subtitle)) $subtitle = date('l, F j, Y');
	$qs ??= '';
	?>


<div class='head' id='titles'>
<?php if ($qs == 'snap'): ?>
<div class='pad'>Now<div id='clock'></div></div>
<?php endif; ?>

<div class='title'>
	<h1><?=$title?></h1>
	<h2  ><?=$subtitle?></h2>
</div>
<?php if (!empty($sunset)): ?>
<div class='pad'>Sunset <?=$sunset?></div>
<?php endif; ?>
</div>

