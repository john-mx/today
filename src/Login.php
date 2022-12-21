<?php

namespace DigitalMx\jotr;

/*
    get password

    check_pwlevel (min)
    	compares current pw level with min, returns true if >=,
    	otherwise opens screen to get pw and sets level in session.
    get_pwlevel()
    	returns pw level from session
    get_pw()
    	opens login window.  Window gets pw.  Submit calls
    		set_pwlevel
    set_pwlevel(pw)
    	gets level from pw.ini; sets session; returns level
*/

use DigitalMx as u;
use DigitalMx\jotr\Definitions as Defs;


class Login
{

	private $pwlevels=[];

	public function __construct(){
	 $inifile = Defs::$Files['passwords'];
	 $this->pwlevels = parse_ini_file(REPO_PATH . '/config/'. $inifile);

	}

	public function check_pw (int $min_pwl = 0) {
		$pwl  = $this->get_pwlevel();
		if ($pwl >= $min_pwl) {
			Log::info('Successful Login',REMOTE);
			return true;
		}
		Log::warning ('Failed Login',REMOTE);
		$this->show_login();

	}

	public function get_pwlevel() {
		return $_SESSION['pw_level'] ?? 0 ;
	}


	public function set_pwl ($pw='') {
		// look up value from pw table
		$pwx = intval($this->pwlevels[$pw] ?? 0);
		$pwl = 0;
		if (!empty($pwx) && is_integer($pwx) && $pwx > 0) {
			$pwl = $pwx;
		}
		$_SESSION['pw_level'] = $pwl;
		return $pwl;
	}

	private function show_login() {

	echo <<<EOF
	<h3>Login Required</h3>
	<p>Please Log In </p>
	<form method = 'post'>
	<input type='hidden' name='type' value='login'>
	<input type=text name='pw' id = 'pw' size=10>
	<input type='submit'>
	</form>

	<script>document.getElementById('pw').focus();</script>
	EOF;
exit;
	}

}
