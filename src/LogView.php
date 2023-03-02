<?php
namespace DigitalMx\jotr;

/*
	script to retrieve log files

*/

class LogView {

	private $dir;
	public function __construct($dir) {

		$this->dir = $dir; // path to logs
	}

	public function list_logs(){
		$filelist = glob("$this->dir/*.log");
#		Utilities::echor($filelist);
	echo "<h3>Log Records</h3>" . NL;
	echo "Click to view contents" . NL;
		echo "<ul>";
		foreach ($filelist as $file){
			$filename = basename($file);
			$urlfile = urlencode($filename);
			echo "<li><a href='/logview.php?$urlfile'>$filename</a>";
		}
		echo "</ul><hr>";
		echo "<li><a href='http://digitalm.pairserver.com/WWW_REPORTS/jotr.digitalmx.com.html'>Web Access Log</a>";

	exit;
	}


	public function show_log($log){

		$logf = $this->dir . "/$log";

		$data = `tail -n 40 $logf`;
		echo "<H3>tail $log</H3>" . NL;
#		echo nl2br($data) . NL;
		echo "<pre>$data</pre>" . NL;
		echo  "<hr>";
		echo "<a href='/logview.php'>View Log List</a>";
		exit;
	}
}
