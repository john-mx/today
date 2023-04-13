<?php
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\LocationSettings as LS;
use DigitalMx\jotr\DisplayManager as DM;


/* Script ends the head section
*/

//U::echor($meta,'meta');

	$animation = $meta['rotation']['animation']??'';
//echo "animation: " . $animation;exit;
	$bodyAction = '';

	if ($animation == 'scroll') {
		$bodyAction = "onLoad='pageScroll()'";

	} elseif( $animation == 'snap'){
		$bodyAction = "id='rotator' onLoad='load_snap();'";

	}

?>


</head>
<body <?=$bodyAction?> >
