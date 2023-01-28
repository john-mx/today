<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Utilities as U;

/* Page Manager starts every page,
	selects content to go on it
	and ends it.

	from some pubic page...
	PM->showPage(name);

	show_page does
		show head
		add headers
		start body
		get content from display manager
		send to template
		close page

	meta = [
		qs = snap|scroll  action command
		page = page name (basename of file)


*/



class PageManager {

// classes
private $Plates;
private $CM;
private $DM;
private $Cal;
private $Camps;



public function __construct($c){
	$this->Plates = $c['Plates'];
// 	$this-> CM = $c['CacheManager'];
// 	$this->Cal = $c['Calendar'];
// 	$this->Camps = $c['Camps'];
	$this->DM = $c['DisplayManager'];

}

public function showPage(string $pagename, string $query_string = '') {


	echo $this->startHTML();
	echo $this->startHead($pagename,$querystring);

	switch ($pagename) {
		case 'pages':
			echo "</head><body>";
			echo $this->Plates->render('pages');
			break;
		case 'pager' :
			echo "</head><body>";
			switch ($qs){
				case 'weather':
					$z = $this->DM->build_topic_weather();
					$params = array('wslocs'=>['hq','jr'],'wsdays'=>3,'wsstart'=>1,);
					echo $this->Plates->render('weather',array_merge($z,$params??[]));
					break;
				default:
					echo "Page not recognized: $qs";
			}
			break;
		case 'rotate' :

			break;



		default:
			echo "</head><body>";
			echo "Unrecognized page $page";
	}

	echo $this->endHTML();
}



private function startHead($page,$pagename,$querystring){


/* start with ['title'=>title,'pcode'=style code,'extra'=>extra headers];
	A working title is creeated from title . pcode (site repo)
	This is used in the title header tag, so that's what shows up as the page's title in the browser.
	The title without embellishment is used at the top of the page,
	UNLESS the title is 'Today'.
	For title = 'Today',  the title is the current date instead.

	So for the today page, use "Today" as the title.  For all other pages,
	use a reasonable title for the top of the page.

	pcode modifies page for variation animations.
	extra is additinal codee to put in head (e.g., <style> section)
	*/

	switch ($querystring) {
		case 'snap':
			$animation='snap';
			$refresh_header = '<meta http-equiv="refresh" content="900" >';
			$scbody = "onLoad=load_snap()";
			$animation_script = "<script src='/js/load_snap.js'></script>";
			break;
		case 'scroll':
			$animation = 'scroll';
			$refresh_header = '<meta http-equiv="refresh" content="900" >';
			$scbody='onLoad="pageScroll()"';
			$animation_script = "<script src='/js/scroll_scripts.js'></script>";
			break;
		default:
			$animation = '';
			$refresh_header = '';
			$scbody = '';
			$animation_script = "";

	}



// for rotation..
	$rotate ??= [];
	$rdelay ??=13;
	//$pagelist = json_encode($pages);
//Utilities::echor($pagelist,'pagelist');


			} else if ($qs == 'snap'){
				$rdelay ??=13;
				//echo "rdealy $rdelay" . BR; exit;
				$pages = [];
				if ($rotate){
					foreach ($rotate as $pid){
						$pages[] = '#page-'.$pid;

				$added_headers .= "<script>var pageList = $pagelist;var rdelay = $rdelay;</script>" .NL;
		}

$titlex = $pagename
			. $animation? ":$animation": ''
			. ' (' .SITE  .') ';
			#.  '[' . REPO .']'


$maints = U::addTimestamp('/css/main.css');
$printts = U::addTimestamp('/css/print.css');

?>
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />


	<title><?=$titlex?></title>

	<script src='/js/check_time.js'></script>
	<script src='/js/hide.js'></script>
	<script src='/js/clock.js'></script>

	<script src='/js/help.js'></script>
	<!-- <link rel='stylesheet' href = '/css/Frutiger.css' /> -->

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,500&display=swap" rel="stylesheet">
	<!-- Google Rubik sans, weights 300,400, 500, 600 ,700 ,800.  Italic in 400 and 500 -->

	<link rel='stylesheet' href = '<?=$maints?>' />
	<link rel='stylesheet' href = '<?=$printts?>' />
	<?=$refresh_header?>


<?php
}

private function endHTML ($show_version=false) {
	if ($show_version){
		echo "
	<hr>
	<p id='bottom' class='right' style='font-size:9px;'>" . VERSION . "<br/>
	Rendered " ,  date('n/j H:i') . "</p>\n";
	}
echo "</body></html>\n";
}

private function startHTML(){
	echo <<<EOT
	<!DOCTYPE html>
	<html lang="en">
EOT;
}
} #end class
