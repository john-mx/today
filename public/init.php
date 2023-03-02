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

$home_ip = '142.202.78.84'; // used to cut down on log reporting

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
//session_destroy();

$session_timeout = 7*24*60*60; // 7 days

//Set the maxlifetime of the session
ini_set( "session.gc_maxlifetime", $session_timeout );

//Set the cookie lifetime of the session
ini_set( "session.cookie_lifetime", $session_timeout );

//Start a new session
session_start();

//Set the default session name
$s_name = session_name();

//Check the session exists or not
if(isset( $_COOKIE[ $s_name ] )) {
    setcookie( $s_name, $_COOKIE[ $s_name ], time() + $session_timeout, '/' );

   // echo "Session is created for $s_name.<br/>";
}



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



if (LIVE){
    ini_set('display_errors', 0);
} else {
    ini_set('display_errors', 1);
}




$loginfo =array ('LOGINFO' => [
	gethostbyaddr($_SERVER['REMOTE_ADDR']) ?? '?',
	$_SERVER['REMOTE_ADDR'],
]);

define ('REMOTE', $loginfo);
//U::echor(REMOTE);
$loginfo ['url'] = $_SERVER['REQUEST_URI'];
// skip local logins
if (PLATFORM=='remote' && ($_SERVER['REMOTE_ADDR'] != $home_ip) ) Log::info("Init started" ,$loginfo);

// if on jotrx repo then make sure caches are updated.
$CM = $container['CacheManager'];
// took this out.  alert comes up on wrong screen and crashes the browser.
if (0 && REPO == 'jotrx'){
	$refresh_request_age=$CM -> ageCache('refRequest');
	if ( $refresh_request_age > 100){
		echo <<<EOT
			<script>
			if (confirm("Caches are stale. ($refresh_request_age mins)  Click OK to update or cancel to ignore.")){
				window.location.assign("/refresh.php?all");
			} // else do nothing
			</script>
EOT;
		$CM->rebuild_ref_request();
	}
}





 define ('INIT',1);
//echo basename(__FILE__) . " [". __LINE__ ."]" . BR; exit;
//EOF

