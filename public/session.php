<?php
namespace DigitalMx\jotr;
use DigitalMx\jotr\Definitions as Defs;
use DigitalMx\jotr\Utilities as U;


//BEGIN START
	require_once 'init.php';
//echo "At " . basename(__FILE__) . " [". __LINE__ ."]" . BR;
	$Plates = $container['Plates'];
	$DM = $container['DisplayManager'];

//END START

if (!$container['Login']->checklevel(basename(__FILE__))) exit;
$meta=array('meta'=>[
	'file' => basename(__FILE__),
	'title' => 'Current Session',
	]);


echo $Plates->render('head',$meta);
echo $Plates->render('body',$meta);
echo $Plates->render('title',$meta);

if (isset($_SESSION['loginTime'])):
	echo "Login " . date('r',$_SESSION['loginTime']);
else: echo "Not logged in";
endif;

U::echor($_SESSION,'Session');

?>
<hr>
<a href='/logout.php'>Log Out</a>

<?php echo $Plates->render ('sig'); ?>
