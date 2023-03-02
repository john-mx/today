<?php
namespace DigitalMx\jotr;

use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;

/*
    get password

  	call with Login->checkLevel(level,$page)
  	where level is the required security level (0-9)
  	$page is the name of the calling page from PageContent.

  	If session already has loginLevel >= level, returns true.
  	Else, shows a login screen.
  	User enters login code in form, that goes to /login.php.
  	Form sent back to this script in setLevel(post).
  	pw checked for level in pw.ini, SESSION['loginLevel'] set,
  	passes sender (page) back to login.php, where which calls PC->showPage($page) again.

  	if sender starts with /, then it goes to that page
  	otherwise, it uses showPage.
  	So works wiith new or old style urls

*/

class Login
{

	private $sender;
	private $pageLevel;
	private $pwdata=[];
	private $userLevel;
	private static $loginLife = 30*60; // 30 minutes lifetime

	public function __construct(){
		$this->pwdata = parse_ini_file(REPO_PATH . '/config/'. Defs::getFile('passwords'),true);
//  		U::echor($this->pwdata);
		$this->userLevel = $_SESSION['loginLevel'] ?? false ;

	}
	private function getPageLevel($page) {
		return intval($this->pwdata['pages'][$page] ?? 0);
	}

	public function checkSite($site) {
		$siteLevel = $this->pwdata['site'][REPO];
		if(!siteLevel) return true;

		if (!$this->userLevel):
			$loginMessage = "Login is required for this site";
		elseif ($this->userLevel < $siteLevel):
			$loginMessage = "You need a higher level login for this page.";
		elseif ((time() - $loginTime) > self::$loginLife):
			$loginMessage = "Login has expired.";
			unset ($_SESSION['loginLevel']);
		else:
			// reset the login time to now
			// expires applies to time since last used
			$_SESSION['loginTime'] = time();
			return true;
		endif;

		$this->showLogin($sender,$loginMessage);



	}
	public function checkLevel ($sender='') {
		if (!$sender) throw new \RuntimeException("Cannot check level without sender");
// 	echo "Checking sender $sender" . BR;

		// check for local user; skip all login tests
		// REPO is git root; LOCAL is root defined in Initialize
		if (REPO == LOCAL) {
			$this->userLevel = 9;
			return true;
		}
		$pageLevel = $this->getPageLevel($sender);
		$loginTime = $_SESSION['loginTime'] ?? 0;

//		echo "ulevel " . $this->userLevel . BR;
// 		U::echor($_SESSION,'session');

		if ( !$pageLevel) return true;

		if (!$this->userLevel):
			$loginMessage = "Login is required for this page.";
		elseif ($this->userLevel < $pageLevel):
			$loginMessage = "You need a higher level login for this page.";
		elseif ((time() - $loginTime) > self::$loginLife):
			$loginMessage = "Login has expired.";
			unset ($_SESSION['loginLevel']);


		else:
			// reset the login time to now
			// expires applies to time since last used
			$_SESSION['loginTime'] = time();
			return true;
		endif;
		if (0) $loginMessage .= " sender: " . $sender . "; repo: " . REPO;
		$this->showLogin($sender,$loginMessage);


	}
	public function hasAccess($page){
		$pageLevel = $this->getPageLevel($page);
		if ($this->userLevel >= $pageLevel) return true;
		return false;

	}

	public function logOut($sender=false){
		unset ($_SESSION['loginLevel']);
		unset ($_SESSION['loginTime']);

	echo <<<EOT
	<script>
	alert('You have been logged out.');
	</script>
EOT;
	$this->goSender($sender);

}

	private function goSender($sender){
	//echo "Going to $sender" . BR;
	if ($sender == REPO) $sender = '';
		if (!$sender || substr($sender,-4) == '.php')
			echo "<script>window.location.assign ('/$sender');</script>";
	 	//elseif ($sender) $PM->showPage($sender);
		else throw new \RuntimeException("No sender in Login $sender");

	}

	private function getLevelFromPw($pw){
		$pwx = intval($this->pwdata[REPO][$pw] ?? 0); // get level from password

		return $pwx;
	}

	public function setLevel ($post) {
		// look up value from pw table
// 		U::echor($post,'post');
		$pw = $post['pw'];
		$sender = $post['sender'];
		$pwLevel = $this->getLevelFromPw($pw);
// echo "pwl $pwLevel" . BR; exit;
		if ($pwLevel >0){
			$_SESSION['loginLevel'] = $pwLevel;
			$_SESSION['loginTime'] = time();
			$this->userLevel = $pwLevel;
		}

		$this->goSender($sender);
		return true;

	}

	private function showLogin($sender,$message) {

	echo <<<EOF
	<h3>Login Required</h3>
	<p>$message </p>
	<form method = 'post' action = '/login.php'>
	<input type='hidden' name='type' value='login'>
	<input type='hidden' name='sender' value="$sender">
	<label>Your name: <input type='text' name='uname'></label>
	<label >Password:  <input type='password' name='pw' id = 'pw' size=10> </label>
	<input type='submit'>
	</form>

	<script>document.getElementById('pw').focus();</script>
	EOF;
	exit;
	}

}
