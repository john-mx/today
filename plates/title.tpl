<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


	//$trial = "<p>Software in Development</p>";
	$trial ??= '';


	$qs ??= '';
	$local_site ??='';
	$subtitle ??='';
	$title ??=$subtitle;

	$local_site = Defs::getLocName($local_site);
	$local_head = ($local_site)?
		"<div><b>Welcome to the $local_site</b></div>"
		: '';
?>
<div class='head' style='display:flex;align-items:flex-end;' id='titles'>

<div class='pad' style='flex:1;justify-content:flex-start;' onClick = 'getLocal();'> <?php if ($qs == 'snap'):?>Now<br /><div id='clock'> </div>
<?php endif; ?>
</div>

<div class='title' style='flex:1;justify-content:flex-center;flex-grow:8'>
	<h1 style='font-weight:700'>Today in Joshua Tree National Park</h1>


		<h1><?=$title?></h1>
</div>

<div class='pad'style='flex:1;justify-content:flex-end;'>
<?php if (!empty($sunset)): ?>Sunset <br /><?=$sunset?>
<?php endif; ?>
</div>

</div>
<?php if ($local_head && $local_site !== 'None'): ?>
		<p class='center'><b>Welcome to the <?=$local_site?></b></p>
	<?php endif;?>
