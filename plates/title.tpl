<?php
	use DigitalMx\jotr\Definitions as Defs;
	//$trial = "<p>Software in Development</p>";
	$trial ??= '';
	$title = 'Today in Joshua Tree National Park';
	if(empty($subtitle)) $subtitle = date('l, F j, Y');
	$qs ??= '';
	$local_site ??='other';

	$local_site = Defs::$sitenames[$local_site] ?? '';
?>
<div class='head' id='titles'>
<?php if ($qs == 'snap'): ?>
<div class='pad' onClick = 'getLocal();'>Now<div id='clock'></div></div>
<?php endif; ?>

<div class='title'>
	<h2><?=$title?></h2>

	<h2><?=$subtitle?></h2>

</div>
<?php if (!empty($sunset)): ?>
<div class='pad'>Sunset <?=$sunset?></div>
<?php endif; ?>
</div>

