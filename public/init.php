<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


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
//session_destroy();
//session_set_cookie_params(60*2,"/"); // 48 hours
if (!session_start([
	'cookie_lifetime' => 3600 * 48,  //48 hours
	])){
		die ("Failed to initialize session");
	}
//setcookie('test_cookie', 'cake',time() + 120, '/', 'jotr.local');
ini_set("pcre.jit", "0"); #required for preg_match();




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




$loginfo =array ('LOGINFO' => [
	gethostbyaddr($_SERVER['REMOTE_ADDR']) ?? '?',
	$_SERVER['REMOTE_ADDR'],
]);

define ('REMOTE', $loginfo);

$loginfo ['url'] = $_SERVER['REQUEST_URI'];
Log::info("Init started" ,$loginfo);




 define ('INIT',1);
//echo basename(__FILE__) . " [". __LINE__ ."]" . BR; exit;
//EOF
