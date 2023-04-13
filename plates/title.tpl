<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;
use DigitalMx\jotr\DisplayManager as DM;
use DigitalMx\jotr\Login as Login;

/* Script ends the head section and then builds the
	title.  there is one for web view and and different one
	for printed page.
*/

//U::echor($meta,'meta',STOP);

	$animation = $meta['rotation']['animation']??'';
	$local_site = $_SESSION['local']['local_site'] ?? '';

	$local_name = LS::getLocName($local_site);
	$local_head = ($local_site && $local_site !== 'none')?
		"Welcome to the $local_name"
		: 'Today in Joshua Tree National Park';



	$animated = false;
	if ($animation == 'scroll') $animated = true;
	if( $animation == 'snap')$animated = true;

	$sunset = $meta['sunset'] ?? '';

?>



<?php
	// check if user has right permission for the file
	if (empty($file = $meta['file'])) throw new Exception ("No file defined in meta");
	if (!$this->checkLevel(REPO)) {echo "failed repo";exit;}
	if (!$this->checkLevel($file)) exit;
	$title = $meta['title'];
?>
<div class='head' id='head'  >

	<div class='pad no-print' style='justify-content:flex-start;' onClick = 'getLocal();'> <img src='/images/trans1x1.png' style='margin-top:4px;height:5rem;' /><br/><?php if ($animation):?>Now<br /><div id='clock'></div>
		<?php endif; ?>
	</div>

	<div class='title'  style='justify-content:flex-center;flex-grow:8;'>
		<div class='no-print'>
		<h1><?=$local_head ?></h1>
		<h2><?=$title?></h2>
		</div>
		<div class='print-only'>
		<h1>Joshua Tree</h1>
		</div>
	</div>

	<div style='justify-content:flex-end'  >
		<div class='print-only'>
		<p style='text-align:left;margin-top:3px;font-size:0.7rem;'>National Park Service<br />U.S. Department of the Interior<br /><br />Joshua Tree National Park</p>

		</div>
	</div>
	<div style='justify-content:flex-end; width:1rem;'>&nbsp;</div>

	<div  style='justify-content:flex-end;margin-right:1rem;text-align:center;'>

		<img src='/images/shield-flat-alpha-696.png' style='margin-top:4px;height:5rem;' class='auto' />

		<?php if (!empty($sunset)): ?> <div class='no-print' style='margin:0;'>Sunset <br /><?=$sunset?></div>
		<?php endif; ?>
	</div>

</div>

<div class='print-only'>
<div style='display:flex;justify-content:space-between;'>
	<h1> <?=$local_head?></h1>
	<h1> <?=$title?></h1>
</div>

 <hr>
</div>

