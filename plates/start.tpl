<?php
	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Today;

// start with $meta = [title,pcode];
	$pcode = $meta['pcode'] ?? '';
	$title = $meta['title'] ?? 'Today in the Park';


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

	<title><?=$title?> (<?=PLATFORM?>)</title>
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
<img src='/images/Shield-7599-alpha.png' alt="NPS Shield" />
</td></tr>
</table>

