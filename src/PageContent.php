<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Utilities as U;
use DigitalMx\jotr\CacheSettings as CS;

class PageContent {

	private $DM;
	private $CM;
	private $Plates;
	private $Admin;



	function __construct($c){
		$this->DM = $c['DisplayManager'];
		$this->CM = $c['CacheManager'];
		$this->Plates = $c['Plates'];
		$this->Admin = $c['Admin'];

	}


public function getContent(string $page){
	switch ($page) {
		case 'ranger':
			return 'Hello, Ranger' . BR;
			break;

		case 'page-weather' :
			$z = $this->DM->build_topic_weather();
			$params = array('wslocs'=>['hq','jr'],'wsdays'=>3,'wsstart'=>1,);
			echo $this->Plates->render('weather',array_merge($z,$params??[]));
			break;

		case 'today':

			$z = $this->DM->getTopics(
			['admin','cal','weather','uv','light','camps','air','current','fees']
			);

			$this->Plates->addData($z,
			['today', 'notices','conditions','advice','weather','alerts']);

			echo $this->Plates->render('today',$z);
			break;

		case 'rotate':
			$z = $this->DM->getTopics(
			['admin','cal','weather','uv','light','camps','air','current','fees']
			);
			$this->Plates->addData($z,
			['condensed','notices','conditions','advice','weather-tv',
			'alerts']);

			echo $this->Plates->render('condensed');
			break;
		case 'about':
			echo $this->Plates->render('about');
			break;
		case "pages":
			echo $this->Plates->render('pages');
			break;

		case "refresh":
			if (empty($this->qs)){
				$clist = CS::getCacheList(); sort ($clist);
				$coptions = U::buildOptions($clist);
				echo $this->Plates->render ('refresh',['coptions'=>$coptions]);

			} elseif ($this->qs == 'all'){
				echo "Starting all cache refresh, normal timing." . BR;
				$CM->refreshAllCaches();
			} elseif ( $this->qs == 'force_all') {
				echo "Starting all cache refresh, forced" . BR;
				$CM->refreshAllCaches(true);
			} elseif (in_array($this->qs,$clist)){
				echo "Force refresh cache " . $this->$qs . BR;
				$CM->refreshCache($this->qs,true);
			} else {
				echo "Error: $qs cache not found.";
			}
			break;

		case 'admin':
			// deal with login

			$Login = new Login();
			if (! $Login->checkLevel(2,$page)) exit;  // will display login screen or return true;

			$topics = $this->Admin->prepare_admin();
			//U::echor($topics);
			$this->Plates->addData($topics,['admin','camp-admin','cal-admin']);
			echo $this->Plates->render('admin');
			break;

		case 'help':
			$q = $_SERVER['QUERY_STRING'];
			echo $this->Plates->render('help/' . $q);
			break;






		default:
			return 'Page Not Found' . BR;
	}
}
}


