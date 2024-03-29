<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;
use DigitalMx\jotr\DisplayManager as DM;
use DigitalMx\jotr\Login as Login;


	$title =$meta['title'];
	// $animation = $meta['rotation']['animation']??'';
	$local_site = $_SESSION['local']['local_site'] ?? '';

// 	$local_name = LS::getLocName($local_site);
	$local_head = ($local_site && $local_site !== 'none')?
		"Welcome to the $local_name"
		: 'Today in Joshua Tree National Park';


	$bodyAction = '';
// 	$sunset = $meta['sunset']??'';
	$animated = false;
// 	if ($animation == 'scroll') {
// 		$bodyAction = "onLoad='pageScroll()'";
// 		$animated = true;
// 	} elseif( $animation == 'snap'){
// 		$bodyAction = "id='rotator' onLoad='load_snap();'";
// 		$animated = true;
// 	}
?>
<!--
<?php if ($animation):?>
	<script src='/js/clock.js'></script>
<?php endif; ?>
 -->

<!-- hide clock and sunset in title if no javascript -->
<!--
<?php if ($_SESSION['local']['hide_js'] ?? false ):  ?>
	<style> .pad{display:none;}</style>
<?php endif; ?>

<noscript>
	<style>
		.pad {display:none;}
	</style>
</noscript>
 -->

</head>
<body <?=$bodyAction?> >

<?php if (empty($meta['file'])) throw new Exception ("No file for meta in title");
	if (!$this->checkLevel(REPO)) {echo "failed repo";exit;}
	if (!$this->checkLevel($meta['file'])) exit;

?>
<div class='head' id='head'  >

	<div class='pad no-print' style='justify-content:flex-start;' onClick = 'getLocal();'><p>&nbsp;&nbsp;&nbsp;</p>
		</div>

	<div class='title' style='text-align:left;flex-grow:8;'>
	<p style='font-size:2.5rem;font-weight:bold;margin:10px;vertical-align:center;'>Joshua Tree</p>

	</div>

	<div style='justify-content:flex-end'  class='print-only'><p style='text-align:left;margin-top:3px;font-size:0.7rem;'>National Park Service<br />U.S. Department of the Interior<br /><br />Joshua Tree National Park</p>
	</div>
<div style='justify-content:flex-end; width:1rem;'>&nbsp;</div>
	<div  style='justify-content:flex-end;margin-right:1rem;text-align:center;'>

		<img src='/images/shield-flat-alpha-696.png' style='margin-top:4px;height:5rem;' class='auto' />



	</div>

</div>
<div style='display:flex;justify-content:space-between;'>
<h1> <?=$local_head?></h1>

<h1> <?=$title?></h1>
</div>

