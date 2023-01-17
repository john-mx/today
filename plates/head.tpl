<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


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

$rdelay ??=13;
//echo "rdealy $rdelay" . BR; exit;
$pagel = [];
if ($rotate){
	foreach ($rotate as $pid){
		$pagel[] = '#page-'.$pid;
	}
}

	$pagelist = json_encode($pagel);

//Utilities::echor($pagelist,'pagelist');

//$rdelay = 15; #delay on rotation

//echo "qs: $qs"; exit;
	switch ($qs) {
		case '';

			break;
		case 'scroll':
			$scbody='onLoad="pageScroll()"';
			$added_headers .= "<style>html {scroll-behavior: smooth;}</style>" .NL;
			$added_headers .= "<script src='/js/scroll_scripts.js'></script>".NL;


			$added_headers .= '<meta http-equiv="refresh" content="900" >' .NL;
			break;


		case 'snap':

			$scbody = "onLoad=load_snap()";
			$added_headers .= '<meta http-equiv="refresh" content="900" >'.NL;
			$added_headers .= "	<script src='/js/snap2.js'></script>".NL;
			$added_headers .= '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">';
			$added_headers .= "<script src='/js/load_snap.js'></script>";
			$added_headers .= "<script>var pageList = $pagelist;</script>" .NL;
			$added_headers .= "<script>var rdelay = $rdelay;</script>" .NL;
			break;

		default:
			Utilities::echoAlert ("Undefined option: $pcode");
				echo "<script>window.location.href='/today.php';</script>";
				exit;
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

	<script src='/js/check_time.js'></script>
	<script src='/js/hide.js'></script>
	<script src='/js/clock.js'></script>

	<script src='/js/help.js'></script>
	<link rel='stylesheet' href = '/css/Frutiger.css' />
	<link rel='stylesheet' href = '<?=U::addTimestamp('/css/main.css')?>' />
	<link rel='stylesheet' href = '/css/print.css' />

	<?=$added_headers?>

</head>
<body <?=$scbody?> >


