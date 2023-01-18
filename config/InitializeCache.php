<?php
namespace DigitalMx\jotr;
/* initial settings for cache files that can't be refreshed externally */

class InitializeCache {

public static $admin = [

		"announcements" => "Testing an announcement <br /> with a br in it.",
		"updated" =>  "13 Jan 08 => 51",
		"pithy" =>  "Today is Take a Ranger to Lunch Day!",
		"fire_level" =>  "Low",
		"alert_alt" =>  "In The Park...\r\n<span style='color => crimson;font-weight => bold;'>No Food • No Water • No Cell Service</span>\r\n<span style='color => green;'>Enjoy the park safely. Do Not Die Today!</span>",
		"advice" =>  "See the Indigenous People's exhibit at the Visitor Center in Twentynine Palms.	 \r\nMake sure you have warm clothing. Weather can change rapidly.  \r\n\r\nPut the park on your phone! Get the NPS app to explore the park without cell service.",

	"uncertainty" =>  "3",
	"rotate" =>  [
		"today",
		"notices",
		"weather",
		"events"
	],
	"rdelay" =>  "10",
	"alertA" =>  [
		"title" => "Important Alert",
		"text" => "Alert text here",
		"expires" => "1751587200",
		],

];

public static $calendar = [
];

public static $camps = [
		"ic" => [
			"status" =>"Reservation",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
		"jr" => [
			"status" =>"Reservation",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
		"sp" => [
			"status" => "Reservation",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
		"hv" =>  [
			"status" =>"First",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
		"be" =>  [
			"status" =>"First",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
		"wt" =>  [
			"status" =>"First",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
		"ry" =>  [
			"status" =>"Reservation",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
		"br" =>  [
			"status" =>"Reservation",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
		"cw" =>  [
			"status" =>"Reservation",
			"notes" => "",
			"open" => 0,
			"asof" => 1673899787,
		],
	];

public static $campsRec = [
		"ic" => [
			"open" => 0,
			"asof" => 1673899787,
		],
		"jr" => [
			"open" => 0,
			"asof" => 1673899787,
		],
		"sp" => [
			"open" => 0,
			"asof" => 1673899787,
		],
		"ry" =>  [
			"open" => 0,
			"asof" => 1673899787,
		],
		"br" =>  [
			"open" => 0,
			"asof" => 1673899787,
		],
		"cw" =>  [
			"open" => 0,
			"asof" => 1673899787,
		],
	];


}
