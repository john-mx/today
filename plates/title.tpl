<?php
	$trial = "<p>Software in Development</p>";
	$title = 'Today in Joshua Tree National Park';
	$subtitle ??= date('l, F d, Y');

	?>

<style>
div.head {
	text-align:center;
	background-color:black;
	color:white;
	margin:0;
	margin-bottom:0.5rem;
	padding:0.5em;
	width:100%;

	}

div.head .title {
	display:inline-block;
	width:70%;
}

div.head .title>h1 {
	font-size:1.5em;
	color:white;
	font-weight:bold;
	margin:0;

	}

div.head  p {
	font-size:0.7rem;
	color:red;
	margin:0;
	}

div.head .title>h2 {
	font-size:1.2em;
	color:white;
	font-weight:bold;
	margin:0.5em;

	}
div.head  .pad {
		width:10%;
		display:inline-block;
	}

</style>

<div class='head'>
<div class='pad'></div>
<div class='title'>
	<h1><?=$title?></h1>
	<h2  ><?=$subtitle?></h2>
</div>

<div class='pad'><?=$trial?></div>
</div>

