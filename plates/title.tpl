<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


	//$trial = "<p>Software in Development</p>";
	$trial ??= '';


	$qs ??= '';

	$subtitle ??='';
	$title ??=$subtitle;

	$local_site = $_SESSION['local']['local_site'] ?? '';

	$local_name = Defs::getLocName($local_site);
	$local_head = ($local_site && $local_site !== 'none')?
		"<div><b>Welcome to the $local_name</b></div>"
		: 'Today in Joshua Tree National Park';
?>

<div class='head' id='titles' >

	<div class='pad' style='justify-content:flex-start;' onClick = 'getLocal();'> <?php if ($qs == 'snap'):?>Now<br /><div id='clock'> </div>
		<?php else: ?>&nbsp;&nbsp;&nbsp;<br/><?php endif; ?>
		</div>

	<div class='title' style='justify-content:flex-center;flex-grow:8'>
	<h1><?=$local_head ?></h1>
		<h2><?=$title?></h2>
	</div>

	<div class='pad'style='justify-content:flex-end;'>
		<?php if (!empty($sunset)): ?>Sunset <br /><?=$sunset?>
		<?php endif; ?>
	</div>
</div>



