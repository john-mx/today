<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


	use DigitalMx\jotr\DisplayManager as DM;

/* start with ['title'=>title,'pcode'=style code,'extra'=>extra headers];
	A working title is creeated from title . pcode (platform)
	This is used in the title header tag, so that's what shows up as the page's title in the browser.
	The title without embellishment is used at the top of the page,
	UNLESS the title is 'Today'.
	For title = 'Today',  the title is the current date instead.

	So for the today page, use "Today" as the title.  For all other pages,
	use a reasonable title for the top of the page.

	pcode modifies page for variation animations.
	extra is additinal codee to put in head (e.g., <style> section)
	*/

;

	$titlex = $meta['file'];
	$rotation=$meta['rotation'] ?? [];
	$animation = $meta['rotation']['animation'] ?? '';


	if (! LIVE) $titlex .=  REPO_ID;



//U::echor($rotation,'roation');


$added_headers='';
$hidejs = U::addTimestamp('/js/hide.js');
$maincss = U::addTimestamp('/css/main.css');
$tvcss=U::addTimestamp('/css/tv.css');
//Utilities::echor($pagelist,'pagelist');

if ($animation) {
	$titlex .= ":$animation " ;
	$added_headers .= "<script src='/js/clock.js'></script>\n";
}

if ($animation=='snap'){

	$pagel = [];
	foreach ($rotation['pageIds'] as $pid){
		$pagel[] = '#page-'.$pid;
	}

	if (!$pagel){$pagel[] = '#page-today';}

	// set up js variables needed
	$snapVars = json_encode(array(
		'rdelay'=>$rotation['rdelay'],
		'pageList' =>$pagel,
		'adviceList' =>array_slice($rotation['advice'],1),
		));

	$snapjs = U::addTimestamp('/js/load_snap.js');


	$added_headers .= "<link rel='stylesheet' href='$tvcss' />";
	$added_headers .= "<script src='$snapjs'></script>";
	$added_headers .= "<script>var snapVars = $snapVars;</script>";

			// add global js variables for script
}
elseif ($animation == 'scroll'){
	$added_headers .= "<script src='/js/scroll_scripts.js'></script>";
	$added_headers .= "<link rel='stylesheet' href='$tvcss' />";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!--
<base href='<?=SITE_URL?>'>
 -->

	<title><?=$titlex?></title>
	<meta http-equiv="refresh" content="900" >
	<script src='/js/check_time.js'></script>
	<script src='<?=$hidejs?>'></script>

	<script src='/js/help.js'></script>
	<link rel='stylesheet' href = '/css/Frutiger.css' />
	<link rel='stylesheet' type='text/css' href = '<?=$maincss?>' />



<!-- hide clock and sunset in title if no javascript -->
<noscript> <style> .pad {display:none;} </style> </noscript>
<?php if ($_SESSION['local']['hide_js'] ?? false ):  ?>
	<style> .pad {display:none;} </style>
<?php endif; ?>


	<!--
	<link rel='preconnect' href='https://fonts.googleapis.com'>
<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
	<link href='https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,500&display=swap' rel='stylesheet'>
 -->
	<!-- Google Rubik sans, weights 300,400, 500, 600 ,700 ,800.  Italic in 400 and 500 -->


	<?=$added_headers?>

<!-- end head block -->


