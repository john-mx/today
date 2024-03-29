<?php

namespace DigitalMx\jotr;
use \DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\LocationSettings as LS;
use DigitalMx\jotr\Utilities as U;


/*
    set up paths and params.
    Must work for cron (no _SErVer) as well.
    so avoid env vars
*/

require "MxConstants.php"; #in libmx; in inc
    // BR, NL, BRNL, CRLF, LF, URL_REGEX //

class Initialize
{
    // translate platform into home page
    private static $homes = array(
      '/usr/home/digitalm' => 'remote',
      '/Users/john' => 'local',
    );

    protected  static $db_ini = '/config/db.ini'; # all the connection params
	// designate host/repo that represents live site
	protected $live_id = '[remote:live]';

    protected $repo;
    protected $site; #/beta.amdflames.org


    public function __construct ()
    {
		 date_default_timezone_set('America/Los_Angeles');
      //  $paths = $this->setPaths();

       // $this->repo  = basename($paths ['repo'] ); # jotr, jbeta

       // $this->setIncludes($paths['repo'] );
        $this->setConstants();
      #  $this->startLogger();


    }



    private function setPaths()
    {
        $paths = array();
		// repo is dir name for set of files, not git repo.
		// assume structure is proj:repo:.git,files
        $paths['repo'] = dirname(__DIR__);  #/usr/home...flames/live
        $paths['proj'] = dirname(__DIR__,2);  #/usr/home...flames

        $paths['db_ini'] = $paths['repo'] . self::$db_ini;

        return $paths; //array
    }


    private function setSite(string $name = '')
    {
        $site = (!empty($name) )? $name : $_SERVER['SERVER_NAME'] ;
        // use main site if run from cron (no _SERVER)
        if (empty($site)) throw new Exception ("No site name found");
        return $site;
    }

    private function setConstants()
    {

		 $site = $this->setSite();
		 $platform = $this->setPlatform();#local or remote
		$paths = $this->setPaths();
			$repo  = basename($paths ['repo'] ); # jotr, jbeta

        define ('REPO_PATH',$paths['repo']);
        define ('REPO', $repo);
		define ('PLATFORM', $platform); // live, remote
			define ('REPO_ID','['.$platform .':'. $repo .']');

        define ('SITE_PATH', REPO_PATH . "/public/");
        define ('SITE', $site);
        define ('SITE_URL', 'https://' . $site);
			define ('LOG_DIR', REPO_PATH . "/logs");

			define ('STOP' , true);
			define ('NOSTOP',false); // these are for Utilities::echor utility
			if (file_exists(REPO_PATH . "/data/version")){
				$version = file_get_contents(REPO_PATH . "/data/version");
			} else {
				$version = "Today build date" . date('Y-m-d H:i');
			}
			define ('VERSION', $version);
//echo "Version: " . VERSION; exit;
			define ('TODAY',date('l, F j, Y'));
			define ('LOCAL','jotr'); // local repo directory.  Used for password control in Login.php


			define ('LIVE', (REPO_ID == $this->live_id));
			//echo REPO_ID; echo LIVE?'true':'false';exit;

			// add api keys to evn
			// $envapi = REPO_PATH . "/.envapi";
// 			echo "$envapi  ";
			if (!$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__) )
				die ("Could not create dotenv");
			if (! $dotenv->load() ) die ("dotenv did not load");
      //	U::echor($_ENV); exit;
    }

 	private function setPlatform(){
    // using PWD because it seems to alwasy work, even in cron

        $sig = __DIR__;
		foreach (self::$homes as $home=>$host){
			if (strpos($sig,$home) !== false)
			return $host;
		}
		return null;
	}




} #end class init

//EOF

