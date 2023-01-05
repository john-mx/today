<?php
namespace DigitalMx\jotr;
/**
 Every script run from web must start by running this script
    (scripts run from cron must take care of their own stuff)

  Sets
    constants
    $pdo
    $page
    $member
    $login

    Sets $_SESSION['menu'],['level'], and ['login'] array



**/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

if (!defined ('INIT')){
	if (!session_start()){
	die ("Failed to initialize session");
}
}
ini_set("pcre.jit", "0"); #required for preg_match();


#use DigitalMx\MyPDO;
use digitalmx as u;
use DigitalMx\jotr\Definitions as Defs;

use DigitalMx\jotr\Initialize;
#use DigitalMx\Flames\Login;


   /**
    *  Initialize all the services and constnats.
    *
    *  This file must be in the server home directory (i.e., public_html)
    *  This file is run by
    *  -  require $_SERVER['DOCUMENT_ROOT'] . '/init.php';
    *  or by another way to define location if there is no server
    *
    */


// test to avoid re-running.  cron-ini  also sets this var.
if (defined ('INIT')){ return; } //some init has already run



// set up for longer session lifes
    #ini_set('session.cookie_lifetime', 86400);
    #ini_set('session.gc_maxlifetime', 86400);


// need to get autoload wihtout any _SERVER data
// bercause it has to run from cron as well.
$repoloc = dirname(__FILE__,2);  #repo directory
if (! file_exists( $repoloc .  "/vendor/autoload.php")) {
   throw new Exception ( "no vendor autoload file.  " );
}
require $repoloc . "/vendor/autoload.php";


// set up exceptions under my namespace.  Just so I don't have to put \ in front
class Exception extends \Exception {}
class RuntimeException extends \RuntimeException {}
// use where function fails because some data is missing.
class ResourceException extends \RuntimeException{}

//class DataException extends \Exception {}



// sets paths, constants, requires
$init = new Initialize();


//creates container and services for most of the classes
require  REPO_PATH . "/config/services.php";



if (REPO == 'live'){
    ini_set('display_errors', 0);
} else {
    ini_set('display_errors', 1);
}


date_default_timezone_set('America/Los_Angeles');


$loginfo =array ('LOGINFO' => [
	gethostbyaddr($_SERVER['REMOTE_ADDR']) ?? '?',
	$_SERVER['REMOTE_ADDR'],
]);

define ('REMOTE', $loginfo);

$loginfo ['url'] = $_SERVER['REQUEST_URI'];
Log::info("Init started" ,$loginfo);

//load all the topic data into $y, and
// pass it to all the listed templates

$y = $container['Today']->build_topics();
$container['Plates']->addData($y,
[
	'today','light','notices','conditions','advice','weather',
	'campground', 'summary', 'condensed','campground-wide',
	'alerts','summary','weather','weather-wapi','weather-wgov',
	'weather-wapi-flat','weather-wgovB',

]
);

// u\echor($y);

 define ('INIT',1);

//EOF
