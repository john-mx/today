<?php
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

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

	$qs ??= '';
	$extra ??='';
	$pithy ??='';

	$page ??= 'page?';

	$scbody = '';
	$titlex = $page . ":$qs" . " (" .PLATFORM . ") ";
	$added_headers = $extra;

	$rotate ??= [];
	$pagelist = [];
	if ($rotate){
	$pagelist = '[';
		foreach ($rotate as $pid){
			$pagelist .= "'#page-$pid',";
		}
	$pagelist .="]";
	$rdelay = 15; #delay on rotation

	}
//echo ($pagelist);
	switch ($qs) {
		case '';

			break;
		case 'scroll':
			$scbody='onLoad="pageScroll()"';
			$added_headers .= "<style>html {scroll-behavior: smooth;}</style>";
			$added_headers .= "<script src='/js/scroll_scripts.js'></script>";
			break;

		case 'Xsnap':
			$scbody = "onLoad='startRotation(15)'";
			$added_headers .= "	<script src='/js/snap.js'></script>";
			break;

		case 'snap':
			$scbody = "onLoad=startRotation(15,$pagelist)";
			$added_headers .= "	<script src='/js/snap2.js'></script>";
			break;

		default:
			u\echoAlert ("Undefined option: $pcode");
				echo "<script>window.location.href='/today.php';</script>";
				exit;
	}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <meta http-equiv="refresh" content="900" >
	<base href='<?=SITE_URL?>'>

	<title><?=$titlex?></title>

	<script src='/js/check_time.js'></script>
	<script src='/js/hide.js'></script>

	<link rel='stylesheet' href = '/css/main.css' />
	<link rel='stylesheet' href = '/css/print.css' />

	<?=$added_headers?>

</head>
<body <?=$scbody?> >


