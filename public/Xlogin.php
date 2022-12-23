<?php
namespace DigitalMx\jotr;

require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

	use DigitalMx as u;
	use DigitalMx\jotr\Definitions as Defs;
	use DigitalMx\jotr\Login;
#$Login = $container['Login'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
	show_login();
	echo "</body></html>" . NL;
	exit;
}


$pw = $_POST['pw'];
if (strlen($pw)<4){
	echo "Error: password not correct (1)";
	show_login();
	echo "</body></html>" . NL;
	exit;
}
$login = new Login();

if (! $login->set_pwlevel($pw)){
	echo "Error: password not recognized (2)";
	show_login();
	echo "</body></html>" . NL;
	exit;
}
echo "ok";


################
function show_login() {

echo <<<EOF
<h3>Login Required<h/3>
<p>Please Log In </p>
<form method = 'post'>
<input type='hidden' name='type' value='login'>
<input type=text name='pw' id = 'pw' size=10>
<input type='submit'>
</form>

<script>document.getElementById('pw').focus();</script>
EOF;

}


