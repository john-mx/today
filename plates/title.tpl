<?php
	//$trial = "<p>Software in Development</p>";
	$trial ??= '';
	$title = 'Today in Joshua Tree National Park';
	if(empty($subtitle)) $subtitle = date('l, F d, Y');

	?>


<div class='head'>
<div class='pad'></div>
<div class='title'>
	<h1><?=$title?></h1>
	<h2  ><?=$subtitle?></h2>
</div>

<div class='pad' id='clock'></div>
</div>

