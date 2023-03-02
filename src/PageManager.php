<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\PageContent as PC;

/* Page Manager starts every page,
	selects content to go on it
	and ends it.

	all accesses redirect to index.php
	index runs (is) init, and runs innitialize scripts

	initialize calls
	PM->showPage();

	show_page does
		show head
		add headers
		start body
		call PageContent class
			retrieve data from display mgr
			modify
			send to template
		close page


*/



class PageManager {

// classes
private $Plates;
private $DM;
private $CM;
private $PC;

private $uri;
private $qs;
private $lparams; // rotate params from local
private $aparams; // rotate params from admin
private $body;
private $page;
private $sunset;

public function __construct($c){
	$this->Plates = $c['Plates'];
	$this-> CM = $c['CacheManager'];
// 	$this->Cal = $c['Calendar'];
// 	$this->Camps = $c['Camps'];
	$this->DM = $c['DisplayManager'];
	$this->PC = $c['PageContent'];




	$this->lparams = $_SESSION['local'] ?? [];
	$this->aparams = $this->DM->getAdminParams();
	// [rdelay=>val,rotate=[pagelist]]
	$this->sunset = $this->DM->getSunset();


}
public function parseUri($uri) {

$page = $this->uri = $_SERVER['REQUEST_URI'];
	$qs = $this->qs = $_SERVER['QUERY_STRING'];
	echo "p $page; qs: $qs" . BR;

}

public function showPage($page='') {

	$this->page=$page;
	echo $this->startHTML();
	echo $this->startHead();
	echo $this->body;
	echo $this->showTitles();

//	echo "Hello, Ranger" . BR;
	echo $this->PC->getContent($this->page);

	echo $this->endHTML();
}


private function showTitles() {
	$qs = $this->qs;
	$title ??='';

	$trial = "<p>Software in Development</p>";
	$local_site = $_SESSION['local']['local_site'] ?? '';
	$local_head = ($local_site && $local_site !== 'none')?
			"<div><b>Welcome to the $local_name</b></div>"
			: 'Today in Joshua Tree National Park';
	$sunset = $this->sunset;
	?>
	<div class='head' id='titles' >

		<div class='pad' style='justify-content:flex-start;' onClick = 'getLocal();'> <?php if ($qs == 'snap' || $qs == 'scroll'):?>Now<br /><div id='clock'> </div>
			<?php else: ?>&nbsp;&nbsp;&nbsp;<br/><?php endif; ?>
			</div>

		<div class='title' style='justify-content:flex-center;flex-grow:8'>
		<h1><?=$local_head ?></h1>
			<h2><?=$title?></h2>
		</div>

		<div class='pad'style='justify-content:flex-end;'>
			<?php if (!empty($sunset)): ?>Sunset <br /><?=$sunset?>
			<?php endif; ?>
		</div>
	</div>
<?php
}
private function startHead(){

/*
$page,$pagename,$querystring
start with ['title'=>title,'pcode'=style code,'extra'=>extra headers];
	A working title is creeated from title . pcode (site repo)
	This is used in the title header tag, so that's what shows up as the page's title in the browser.
	The title without embellishment is used at the top of the page,


	So for the today page, use "Today" as the title.  For all other pages,
	use a reasonable title for the top of the page.

	pcode modifies page for variation animations.
	extra is additinal codee to put in head (e.g., <style> section)
	*/

	$maints = U::addTimestamp('/css/main.css');
	$printts = U::addTimestamp('/css/print.css');
	$snapjs = U::addTimestamp('/js/load_snap.js');
	$tvts = U::addTimestamp('/css/tv.css');
	switch ($this->qs) {
		case 'snap':
			$rotate = $this->lparams['rotate'] ?? $this->aparams['rotate'] ?? [];
			$rdelay  = $this->lparams['rdelay'] ?? $this->aparams['rdelay'] ?? '';
			foreach ($rotate as $pid){
				$pages[] = '#page-'.$pid;
			}
			$pagelist = json_encode($pages);

			$animation='?snap';
			$scbody = "<body onLoad=load_snap()>";
			$animation_script = "
			<script>var pageList = $pagelist;
			var rdelay = $rdelay;
			</script>
			<script src='$snapjs'></script>
			" ;
			$extracss = "<link rel='stylesheet' href='$tvts' />";
			break;
		case 'scroll':
			$animation = '?scroll';
			$scbody='<body onLoad="pageScroll()">';
			$animation_script = "<script src='/js/scroll_scripts.js'></script>";
			break;
		default:
			$animation= '';
			$scbody = '<body>';
			$animation_script = "";
			$extracss = '';

	}

	$refresh_header = '<meta http-equiv="refresh" content="900" >'.NL;
	$pagename = $this->page ?: $this->uri;

	$titlex = $pagename
			. $animation;
	if (!LIVE) $titlex . REPO_ID;




$this->body = $scbody;

?>
<head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />


	<title><?=$titlex?></title>

	<script src='/js/check_time.js'></script>
	<script src='/js/hide.js'></script> <!--includes getLocal-->
	<script src='/js/clock.js'></script>
	<script src='/js/help.js'></script>
	<!-- <link rel='stylesheet' href = '/css/Frutiger.css' /> -->

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,500&display=swap" rel="stylesheet">
	<!-- Google Rubik sans, weights 300,400, 500, 600 ,700 ,800.  Italic in 400 and 500 -->

	<link rel='stylesheet' href = '<?=$maints?>' />
	<link rel='stylesheet' href = '<?=$printts?>' />
	<?=$extracss ?? '' ?>
	<?=$refresh_header?>
	<?=$animation_script?>

<!-- other headers go here -->
<!-- $scbody-->
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
