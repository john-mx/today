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
	$local_head = ($local_site && $local_site !== 'None')?
		"<div><b>Welcome to the $local_site</b></div>"
		: 'Today in Joshua Tree National Park';
?>

<div class='head' id='head' >

	<div class='pad' style='justify-content:flex-start;' onClick = 'getLocal();'> <?php if ($qs == 'snap'):?>Now<br /><div id='clock'> </div><?php endif; ?> </div>

	<div class='title' style='justify-content:flex-center;flex-grow:8'>
	<h1><?=$local_head ?></h1>
		<h2><?=$title?></h2>
	</div>

	<div class='pad'style='justify-content:flex-end;'>
		<?php if (!empty($sunset)): ?>Sunset <br /><?=$sunset?>
		<?php endif; ?>
	</div>
</div>



