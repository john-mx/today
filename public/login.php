<?php
namespace DigitalMx\jotr;

require $_SERVER['DOCUMENT_ROOT'] . '/init.php';

// This pages sole purpose is to receive the login form data
// to the Login class

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	$Login = $container['Login'];

	if ($sender = $Login->setLevel($_POST)) {
		// login normally interrups process so no follow on unless successful
		exit;
	}
}
echo "Not allowed.";
