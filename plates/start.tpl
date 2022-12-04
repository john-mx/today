<?php
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

/* start with $meta = [title,pcode];
	A working title is creeated from title . pcode (platform)
	This is used in the title header tag, so that's what shows up as the pages
	title in the browser.
	The title without embellishment is used at the top of the page,
	UNLESS the title is 'Today'.  That supresses the title on the page body
	because on the today page, the title is the current date instead.

	So for the today page, use "Today" as the title.  For all other pages,
	use a reasonable title for the top of the page.
	*/

	$pcode = $meta['pcode'] ?? '';
	$title = $meta['title'] ?? 'Today in the Park';
	$titlex = $title . ":$pcode" . " (" .PLATFORM . ") ";

	$scbody = '';
	$added_headers = "";
	switch ($pcode) {
		case '';

			break;
		case 'scroll':
			$scbody='onLoad="pageScroll()"';
			$added_headers = "<style>html {scroll-behavior: smooth;}</style>";
			break;

		case 'snap':
			$scbody = "onLoad='startRotation(10)'";
			break;


		case 'print':
			$added_headers = "<link rel='stylesheet' media='print' href = '/css/media.css' >";
				u\echoAlert ("Not implemented yet: $pcode");
				echo "<script>window.location.href='/today.php';</script>";
				exit;
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

	<title><?=$titlex?></title>
	<script src='/js/snap.js'></script>
	<script src='/js/check_time.js'></script>
	<script src='/js/hide.js'></script>

	<link rel='stylesheet' href = '/css/main.css' />

	<?=$added_headers?>

</head>
<body <?=$scbody?>>
<table style='width:100%;border-collapse:collapse;'>
<tr style='background-color:black;text-align:right;color:white;'><td style='background-color:black;text-align:right;color:white;'>
Department of the Interior<br>
Joshua Tree National Park
<h1 style='margin:0'>Today in Joshua Tree National Park</h1>
</td><td style='width:80px;'>
<!-- <img src='/images/Shield-7599-alpha.png' alt="NPS Shield" /> -->
</td></tr>
</table>
<?php if ($title !== 'Today'): ?>
<h2><?=$title?></h2>
<?php endif; ?>
