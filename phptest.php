<?php
use DigitalMx\jotr\Utilities as U;

$r = ['root' =>[
	'sub1'=> [
		'l3'=>1,
		'l4'=>2,
		],
	'sub2'=>[
		'l5'=>1,
		'l6'=>2,
	]
	]
	];

U::echor($r);
