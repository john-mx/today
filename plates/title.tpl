<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


	//$trial = "<p>Software in Development</p>";
	$trial ??= '';
	$title = 'Today in Joshua Tree National Park';
	if(empty($subtitle)) $subtitle = date('l, F j, Y');
	$qs ??= '';
	$local_site ??='';

	$local_site = Defs::getLocName($local_site);
	$local_head = ($local_site)?
		"<div><b>Welcome to the $local_site</b></div>"
		: '';
?>
<div class='head' id='titles'>
<?php if ($qs == 'snap'): ?>
<div class='pad' onClick = 'getLocal();'>Now<div id='clock'></div></div>
<?php endif; ?>

<div class='title'>
	<h1 style='font-weight:800'><?=$title?></h1>

	<?php if ($local_head): ?>
		<h2 >Welcome to the <?=$local_site?></h2>
	<?php endif;?>
		<h1><?=$subtitle?></h1>
</div>

<div class='pad'>
<?php if (!empty($sunset)): ?>Sunset <?=$sunset?>
<?php endif; ?>
</div>

</div>
